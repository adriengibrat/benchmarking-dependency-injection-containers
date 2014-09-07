<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark DI</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        $(function () {
            var options = {
                    height: 300,
                    series: [
                        {targetAxisIndex: 0, color: '#FF3333'}, // memory
                        {targetAxisIndex: 1, color: '#FF9900'}, // time
                        {targetAxisIndex: 3, color: '#CCCC99', opacity: 0.5}, // code lines
                        {targetAxisIndex: 2, color: '#CCCCCC', opacity: 0.5}, // files included
                        {targetAxisIndex: 4, color: '#87CEEB', opacity: 0.5} // cycles collected
                    ],
                    vAxes: [
                        {textStyle: {color: '#FF3333'}}, // memory
                        {textStyle: {color: '#FF9900'}}, // time
                        {textPosition: 'none', gridlines: { color: 'transparent'}}, // code lines
                        {textPosition: 'none',  gridlines: { color: 'transparent'}}, // files included
                        {textPosition: 'none', gridlines: { color: 'transparent'}} // cycles collected
                    ]
                },
                $graphs = $('div.graph')
                    .each(function () {
                        var $self = $(this);
                        $self.data({
                            graph: google.visualization.arrayToDataTable($self.data('results')),
                            chart: new google.visualization.ColumnChart(this)
                        });
                    })
            ;
            $('#fixedView')
                .on('change', function () {
                    var settings = this.checked ?
                        $.extend(true, {}, options, {
                            vAxes: [
                                {viewWindowMode:'explicit', viewWindow: {min: 0, max:5}}, // memory
                                {viewWindowMode:'explicit', viewWindow: {min: 0, max:1}}, // time
                                {viewWindowMode:'explicit',  viewWindow: {min: 0, max:15}}, // code lines
                                {viewWindowMode:'explicit', viewWindow: {min: 0, max:30}}, // files included
                                {viewWindowMode:'explicit', viewWindow: {min: 0, max:13000}} // cycles collected
                            ]
                        }) :
                        options
                    ;
                    $graphs
                        .each(function () {
                            var chart = $.data(this, 'chart')
                              , graph = $.data(this, 'graph')
                            ;
                            chart.draw(graph, settings);
                        })
                    ;
                })
                .trigger('change')
            ;
        });
    </script>
</head>
<body>
    <a href="https://github.com/adriengibrat/benchmarking-dependency-injection-containers"><img style="position:absolute;top:0;right:0;border:0;z-index:1" alt="Fork me on GitHub" src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"></a>
    <label>
        <input type="checkbox" id="fixedView" checked/>
        Use fixed Y axis
    </label>
<?php
use Benchmark\Measure;

require __DIR__ . '/vendor/autoload.php';
$measure    = new Measure;
$factory    = function () {
    $bart = new Benchmark\Stubs\Bart;
    $bam = new Benchmark\Stubs\Bam($bart);
    $baz = new Benchmark\Stubs\Baz($bam);
    $bar = new Benchmark\Stubs\Bar($baz);
    return new Benchmark\Stubs\Foo($bar);
};
$benchmarks = array(
    'Auto resolution of object and dependencies (Aliasing Interfaces to Concretes)'  => include 'benchmarks/Auto-resolution-alias-interface-to-concrete.php',
    'Auto resolution of object and dependencies (Register all objects with container)' => include 'benchmarks/Auto-resolution-register-all-objects.php',
    'Factory closure resolution' => include 'benchmarks/Factory-closure-resolution.php',
    'Constructor injection with defined arguments' => include 'benchmarks/Constructor-injection-with-arguments.php',
    'Setter injection with defined setter methods' => include 'benchmarks/Setter-injection-with-methods.php'
);
$graph_json = function (&$benchmark, $title) use ($measure) {
    static $i = 0;
    ++$i;
    $sort = $results = $messages = array();
    foreach ($benchmark as $name => $test) {
        $code      = new ReflectionFunction($test);
        $codeLines = $code->getEndLine() - $code->getStartLine();
        $instance  = $test();
        if (!isset($instance->bar->baz->bam->bart)) {
            $messages[] = $name . ' injected a null parameter to build Foo object (used default parameter) : '
                . preg_replace('/,(\s+)\)\)/', '$1)', str_replace('::__set_state(array', '', var_export($instance, true)));
        }
        $filesInc  = count(array_filter(get_included_files(), function ($file) use ($name) {
            return stristr($file, $name);
        }));
        $memory    = $measure->benchmarkMemory($test);
        gc_collect_cycles();
        $time      = $measure->benchmarkTime($test, [], 200);
        $benchmark[$name] = null;
        $collected = gc_collect_cycles();
        $sort[]    = $name;
        $results[] = array(
            $name,
            $memory[Measure::MEMORY_VALUE] / 1024,
            $time[Measure::BENCHMARK_AVERAGE] * 1000,
            $codeLines,
            $filesInc,
            $collected
        );
    }
    array_multisort($sort, $results);
    array_unshift($results, array(
        'Component',
        'Memory usage for one test in kb',
        'Time per test, average in µs',
        'Lines of code',
        'Approx. included files',
        'Cycle collected after ' . $time[Measure::BENCHMARK_COUNT] . ' tests'
    ));
    ?>

    <h1>Benchmark <?= $i . ' : ' . $title; ?>.</h1>
    <div class="graph" data-results='<?php echo json_encode($results); ?>'></div>
    <ul>
    <?php
    foreach ($messages as $message) : ?>
        <li><?php echo $message; ?></li><?php
    endforeach; ?>

    </ul><?php
};
gc_disable();
foreach ($benchmarks as $title => &$benchmark) {
    $graph_json($benchmark, $title); ?>

    <br/><b>Memory peak : <?php echo  memory_get_peak_usage(true) / 1024 / 1024 ?> Mb</b>
    <?php
}
    ?>
</body>
</html>
