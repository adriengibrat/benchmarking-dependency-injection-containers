<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Benchmark DI</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        var options = {
            height: 300,
            series: [
                {targetAxisIndex: 0, color: '#FF3333'}, // memory
                {targetAxisIndex: 1, color: '#FF9900'}, // time
                {targetAxisIndex: 3, color: '#CCCC99', opacity: 0.5}, // code lines
                {targetAxisIndex: 2, color: '#CCCCCC', opacity: 0.5}, // files included
                {targetAxisIndex: 4, color: '#87CEEB', opacity: 0.5}  // cycles collected

            ],
            vAxes: [
                {textStyle: {color: '#FF3333'}},
                {textStyle: {color: '#FF9900'}},
                {textPosition: 'none'},
                {textPosition: 'none'},
                {textPosition: 'none'},
            ]
        };
    </script>
<?php
use Benchmark\Measure;

error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    'Auto resolution of object and dependencies (Aliasing Interfaces to Concretes)'  => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');//->withArgument('Benchmark\Stubs\Bam');
            $orno->add('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $league->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $league->resolve('Benchmark\Stubs\Foo');
        },
        'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $illuminate->make('Benchmark\Stubs\Foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $zend->get('Benchmark\Stubs\Foo');
        },
        'PHP-DI'     => function () {
            $builder = new DI\ContainerBuilder();
            $builder->useAnnotations(false);
            $phpdi = $builder->build();
            $phpdi->set('Benchmark\Stubs\BazInterface', DI\object('Benchmark\Stubs\Baz'));
            $phpdi->set('Benchmark\Stubs\BartInterface', DI\object('Benchmark\Stubs\Bart'));
            return $phpdi->get('Benchmark\Stubs\Foo');
        }
    ),
    'Auto resolution of object and dependencies (Register all objects with container)' => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\Foo');
            $orno->add('Benchmark\Stubs\Bar');
            $orno->add('Benchmark\Stubs\Bam');
            $orno->add('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $orno->add('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\Foo');
            $league->bind('Benchmark\Stubs\Bar');
            $league->bind('Benchmark\Stubs\Bam');
            $league->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $league->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $league->resolve('Benchmark\Stubs\Foo');
        },
        'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('Benchmark\Stubs\Foo');
            $illuminate->bind('Benchmark\Stubs\Bar');
            $illuminate->bind('Benchmark\Stubs\Bam');
            $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $illuminate->make('Benchmark\Stubs\Foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Foo', 'Benchmark\Stubs\Foo');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Bar', 'Benchmark\Stubs\Bar');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Bam', 'Benchmark\Stubs\Bam');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            return $zend->get('Benchmark\Stubs\Foo');
        },
        'PHP-DI'     => function () {
            $builder = new DI\ContainerBuilder();
            $builder->useAnnotations(false);
            $phpdi = $builder->build();
            $phpdi->set('Benchmark\Stubs\Foo', DI\object('Benchmark\Stubs\Foo'));
            $phpdi->set('Benchmark\Stubs\Bar', DI\object('Benchmark\Stubs\Bar'));
            $phpdi->set('Benchmark\Stubs\Bam', DI\object('Benchmark\Stubs\Bam'));
            $phpdi->set('Benchmark\Stubs\BazInterface', DI\object('Benchmark\Stubs\Baz'));
            $phpdi->set('Benchmark\Stubs\BartInterface', DI\object('Benchmark\Stubs\Bart'));
            return $phpdi->get('Benchmark\Stubs\Foo');
        }
    ),
    'Factory closure resolution' => array(
        'Orno'       => function () use ($factory) {
            $orno = new Orno\Di\Container;
            $orno->add('foo', $factory);
            return $orno->get('foo');
        },
        'League'     => function () use ($factory) {
            $league = new League\Di\Container;
            $league->bind('foo', $factory);
            return $league->resolve('foo');
        },
        'Illuminate' => function () use ($factory) {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('foo', $factory);
            return $illuminate->make('foo');
        },
        'PHP-DI'     => function () use ($factory) {
            $builder = new DI\ContainerBuilder();
            $builder->useAutowiring(false);
            $builder->useAnnotations(false);
            $phpdi = $builder->build();
            $phpdi->set('foo', DI\factory($factory));
            return $phpdi->get('foo');
        },
        'Aura'       => function () use ($factory) {
            $aura = new Aura\Di\Container(new Aura\Di\Factory);
            $aura->set('foo', $factory);
            return $aura->get('foo');
        },
        'Pimple'     => function () use ($factory) {
            $pimple = new Pimple;
            $pimple['foo'] = $factory;
            return $pimple['foo'];
        }
    ),
    'Constructor injection with defined arguments' => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\Bart');
            $orno->add('Benchmark\Stubs\Bam')->withArgument('Benchmark\Stubs\Bart');
            $orno->add('Benchmark\Stubs\Baz')->withArgument('Benchmark\Stubs\Bam');
            $orno->add('Benchmark\Stubs\Bar')->withArgument('Benchmark\Stubs\Baz');
            $orno->add('Benchmark\Stubs\Foo')->withArgument('Benchmark\Stubs\Bar');
            return $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\Bart');
            $league->bind('Benchmark\Stubs\Bam')->addArg('Benchmark\Stubs\Bart');
            $league->bind('Benchmark\Stubs\Baz')->addArg('Benchmark\Stubs\Bam');
            $league->bind('Benchmark\Stubs\Bar')->addArg('Benchmark\Stubs\Baz');
            $league->bind('Benchmark\Stubs\Foo')->addArg('Benchmark\Stubs\Bar');
            return $league->resolve('Benchmark\Stubs\Foo');
        },
        /*'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
        },*/
        'PHP-DI'     => function () {
            $builder = new DI\ContainerBuilder();
            $builder->useAutowiring(false);
            $builder->useAnnotations(false);
            $phpdi = $builder->build();
            $phpdi->set('Benchmark\Stubs\Bart', DI\object('Benchmark\Stubs\Bart'));
            $phpdi->set('Benchmark\Stubs\Bam', DI\object()->constructor(DI\link('Benchmark\Stubs\Bart')));
            $phpdi->set('Benchmark\Stubs\Baz', DI\object()->constructor(DI\link('Benchmark\Stubs\Bam')));
            $phpdi->set('Benchmark\Stubs\Bar', DI\object()->constructor(DI\link('Benchmark\Stubs\Baz')));
            $phpdi->set('Benchmark\Stubs\Foo', DI\object()->constructor(DI\link('Benchmark\Stubs\Bar')));
            return $phpdi->get('Benchmark\Stubs\Foo');
        },
        'Aura'       => function () {
            $aura = new Aura\Di\Container(new Aura\Di\Factory);
            $aura->params['Benchmark\Stubs\Bam']['bart'] = $aura->lazyNew('Benchmark\Stubs\Bart');
            $aura->params['Benchmark\Stubs\Baz']['bam']  = $aura->lazyNew('Benchmark\Stubs\Bam');
            $aura->params['Benchmark\Stubs\Bar']['baz']  = $aura->lazyNew('Benchmark\Stubs\Baz');
            $aura->params['Benchmark\Stubs\Foo']['bar']  = $aura->lazyNew('Benchmark\Stubs\Bar');
            return $aura->newInstance('Benchmark\Stubs\Foo');
        },
        /*'Pimple'     => function () {},*/
        'Symfony'    => function () {
            $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
            $symfony->register('foo', 'Benchmark\Stubs\Foo')->addArgument(new Symfony\Component\DependencyInjection\Reference('bar'));
            $symfony->register('bar', 'Benchmark\Stubs\Bar')->addArgument(new Symfony\Component\DependencyInjection\Reference('baz'));
            $symfony->register('baz', 'Benchmark\Stubs\Baz')->addArgument(new Symfony\Component\DependencyInjection\Reference('bam'));
            $symfony->register('bam', 'Benchmark\Stubs\Bam')->addArgument(new Symfony\Component\DependencyInjection\Reference('bart'));
            $symfony->register('bart', 'Benchmark\Stubs\Bart');
            return $symfony->get('foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->instanceManager()->setParameters('Benchmark\Stubs\Foo', array(
                'bar' => 'Benchmark\Stubs\Bar'
            ));
            $zend->instanceManager()->setParameters('Benchmark\Stubs\Bar', array(
                'baz' => 'Benchmark\Stubs\Baz'
            ));
            $zend->instanceManager()->setParameters('Benchmark\Stubs\Baz', array(
                'bam' => 'Benchmark\Stubs\Bam'
            ));
            $zend->instanceManager()->setParameters('Benchmark\Stubs\Bam', array(
                'bart' => 'Benchmark\Stubs\Bart'
            ));
            return $zend->get('Benchmark\Stubs\Foo');
        }
    ),
    'Setter injection with defined setter methods' => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\Bart');
            $orno->add('Benchmark\Stubs\Bam')->withMethodCall('setBart', array('Benchmark\Stubs\Bart'));
            $orno->add('Benchmark\Stubs\Baz')->withMethodCall('setBam', array('Benchmark\Stubs\Bam'));
            $orno->add('Benchmark\Stubs\Bar')->withMethodCall('setBaz', array('Benchmark\Stubs\Baz'));
            $orno->add('Benchmark\Stubs\Foo')->withMethodCall('setBar', array('Benchmark\Stubs\Bar'));
            return $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\Bart');
            $league->bind('Benchmark\Stubs\Bam')->withMethod('setBart', array('Benchmark\Stubs\Bart'));
            $league->bind('Benchmark\Stubs\Baz')->withMethod('setBam', array('Benchmark\Stubs\Bam'));
            $league->bind('Benchmark\Stubs\Bar')->withMethod('setBaz', array('Benchmark\Stubs\Baz'));
            $league->bind('Benchmark\Stubs\Foo')->withMethod('setBar', array('Benchmark\Stubs\Bar'));
            return $league->resolve('Benchmark\Stubs\Foo');
        },
        /*'Illuminate' => function () {},*/
        'PHP-DI'     => function () {
            $builder = new DI\ContainerBuilder();
            $builder->useAutowiring(false);
            $builder->useAnnotations(false);
            $phpdi = $builder->build();
            $phpdi->set('Benchmark\Stubs\Bart', DI\object('Benchmark\Stubs\Bart'));
            $phpdi->set('Benchmark\Stubs\Bam', DI\object()->method('setBart', DI\link('Benchmark\Stubs\Bart')));
            $phpdi->set('Benchmark\Stubs\Baz', DI\object()->method('setBam', DI\link('Benchmark\Stubs\Bam')));
            $phpdi->set('Benchmark\Stubs\Bar', DI\object()->method('setBaz', DI\link('Benchmark\Stubs\Baz')));
            $phpdi->set('Benchmark\Stubs\Foo', DI\object()->method('setBar', DI\link('Benchmark\Stubs\Bar')));
            return $phpdi->get('Benchmark\Stubs\Foo');
        },
        'Aura'       => function () {
            $aura = new Aura\Di\Container(new Aura\Di\Factory);
            $aura->setter['Benchmark\Stubs\Bam']['setBart'] = $aura->lazyNew('Benchmark\Stubs\Bart');
            $aura->setter['Benchmark\Stubs\Baz']['setBam'] = $aura->lazyNew('Benchmark\Stubs\Bam');
            $aura->setter['Benchmark\Stubs\Bar']['setBaz'] = $aura->lazyNew('Benchmark\Stubs\Baz');
            $aura->setter['Benchmark\Stubs\Foo']['setBar'] = $aura->lazyNew('Benchmark\Stubs\Bar');
            return $aura->newInstance('Benchmark\Stubs\Foo');
        },
        'Symfony'    => function () {
            $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
            $symfony->register('foo', 'Benchmark\Stubs\Foo')->addMethodCall('setBar', array(
                new Symfony\Component\DependencyInjection\Reference('bar')
            ));
            $symfony->register('bar', 'Benchmark\Stubs\Bar')->addMethodCall('setBaz', array(
                    new Symfony\Component\DependencyInjection\Reference('baz')
            ));
            $symfony->register('baz', 'Benchmark\Stubs\Baz')->addMethodCall('setBam', array(
                    new Symfony\Component\DependencyInjection\Reference('bam')
            ));
            $symfony->register('bam', 'Benchmark\Stubs\Bam')->addMethodCall('setBart', array(
                    new Symfony\Component\DependencyInjection\Reference('bart')
            ));
            $symfony->register('bart', 'Benchmark\Stubs\Bart');
            return $symfony->get('foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->configure(new Zend\Di\Config(array(
                'definition' => array(
                    'class' => array(
                        'Benchmark\Stubs\Bam' => array(
                            'setBart' => array(
                                'required' => true,
                                'bart' => array(
                                    'type' => 'Benchmark\Stubs\Bart',
                                    'required' => true
                                )
                            )
                        ),
                        'Benchmark\Stubs\Baz' => array(
                            'setBam' => array(
                                'required' => true,
                                'bam' => array(
                                    'type' => 'Benchmark\Stubs\Bam',
                                    'required' => true
                                )
                            )
                        ),
                        'Benchmark\Stubs\Bar' => array(
                            'setBaz' => array(
                                'required' => true,
                                'baz' => array(
                                    'type' => 'Benchmark\Stubs\Baz',
                                    'required' => true
                                )
                            )
                        ),
                        'Benchmark\Stubs\Foo' => array(
                            'setBar' => array(
                                'required' => true,
                                'bar' => array(
                                    'type' => 'Benchmark\Stubs\Bar',
                                    'required' => true
                                )
                            )
                        )
                    )
                )
            )));
            return $zend->get('Benchmark\Stubs\Foo');
        }
    )
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
            $messages[] = $name . ' injected null  param to build Foo object (used default parameter?) : ' . var_export($instance, true);
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
        'Approx. # of code lines',
        'Files included',
        'Cycle collected after ' . $time[Measure::BENCHMARK_COUNT] . ' tests'
    ));
    ?>
    <h1>Benchmark <?= $i . ' : ' . $title; ?>.</h1>
    <div id="benchmark<?= $i; ?>"></div>
    <script type="text/javascript">
        google.setOnLoadCallback( function () {
            var data = google.visualization.arrayToDataTable([
                <?php echo implode(",\n                ", array_map('json_encode', $results)) . "\n"; ?>
            ]);
            var chart = new google.visualization.ColumnChart(document.getElementById('benchmark<?= $i; ?>'));
            chart.draw(data, options);
        });
    </script>
    <ul>
    <?php
    foreach ($messages as $message) {
        echo '<li>', $message, '</li>';
    }
    echo '      </ul>';
};
gc_disable();
foreach ($benchmarks as $title => &$benchmark) {
    $graph_json($benchmark, $title);
    echo '<br/><b>Memory peak : ', memory_get_peak_usage(true) / 1024 / 1024, 'Mb</b>';
}
?>
</body>
</html>
