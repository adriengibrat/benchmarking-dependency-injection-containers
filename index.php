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
                {targetAxisIndex: 0, color: '#FF6961'},
                {targetAxisIndex: 1, color: '#FFB347'},
                {targetAxisIndex: 2, color: '#CCC', opacity: 0.5},
                {targetAxisIndex: 3, color: '#77DD77', opacity: 0.5}
            ],
            vAxes: [
                {textStyle: {color: '#FF6961'}},
                {textStyle: {color: '#FFB347'}},
                {textPosition: 'none'},
                {textPosition: 'none'}
            ]
        };
    </script>
</head>
<body>
<?php
use Benchmark\Measure;

require __DIR__ . '/vendor/autoload.php';
$measure    = new Measure;
$benchmarks = array(
    'Auto resolution of object and dependencies (Aliasing Interfaces to Concretes)'  => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $orno->add('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $league->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $league->resolve('Benchmark\Stubs\Foo');
        },
        'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
            $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $illuminate->make('Foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $zend->get('Benchmark\Stubs\Foo');
        },
        'PHP-DI'     => function () {
            $phpdi = new DI\Container;
            $phpdi->useAnnotations(false);
            $phpdi->addDefinitions(
                array(
                    'Benchmark\Stubs\BazInterface'  => array(
                        'class' => 'Benchmark\Stubs\Baz'
                    ),
                    'Benchmark\Stubs\BartInterface'  => array(
                        'class' => 'Benchmark\Stubs\Bart'
                    ),
                )
            );
            $phpdi->get('Benchmark\Stubs\Foo');
        }
    ),
    'Auto resolution of object and dependencies (Register all objects with container)' => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\Foo', 'Benchmark\Stubs\Foo');
            $orno->add('Benchmark\Stubs\Bar', 'Benchmark\Stubs\Bar');
            $orno->add('Benchmark\Stubs\Bam', 'Benchmark\Stubs\Bam');
            $orno->add('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $orno->add('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\Foo');
            $league->bind('Benchmark\Stubs\Bar');
            $league->bind('Benchmark\Stubs\Bam');
            $league->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $league->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $league->resolve('Benchmark\Stubs\Foo');
        },
        'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
            $illuminate->bind('Benchmark\Stubs\Bar');
            $illuminate->bind('Benchmark\Stubs\Bam');
            $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $illuminate->make('Foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Foo', 'Benchmark\Stubs\Foo');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Bar', 'Benchmark\Stubs\Bar');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\Bam', 'Benchmark\Stubs\Bam');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $zend->instanceManager()->addTypePreference('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $zend->get('Benchmark\Stubs\Foo');
        },
        'PHP-DI'     => function () {
            $phpdi = new DI\Container;
            $phpdi->useReflection(false);
            $phpdi->useAnnotations(false);
            $phpdi->addDefinitions(
                array(
                    'Benchmark\Stubs\Foo'  => array(),
                    'Benchmark\Stubs\Bar'  => array(),
                    'Benchmark\Stubs\Bam'  => array(),
                    'Benchmark\Stubs\BazInterface'  => array(
                        'class' => 'Benchmark\Stubs\Baz'
                    ),
                    'Benchmark\Stubs\BartInterface'  => array(
                        'class' => 'Benchmark\Stubs\Bart'
                    ),
                )
            );
            $phpdi->get('Benchmark\Stubs\Foo');
        }
    ),
    'Factory closure resolution' => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('foo', function () {
                    $bart = new Benchmark\Stubs\Bart;
                    $bam = new Benchmark\Stubs\Bam($bart);
                    $baz = new Benchmark\Stubs\Baz($bam);
                    $bar = new Benchmark\Stubs\Bar($baz);
                    return new Benchmark\Stubs\Foo($bar);
            });
            $orno->get('foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('foo', function () {
                    $bart = new Benchmark\Stubs\Bart;
                    $bam = new Benchmark\Stubs\Bam($bart);
                    $baz = new Benchmark\Stubs\Baz($bam);
                    $bar = new Benchmark\Stubs\Bar($baz);
                    return new Benchmark\Stubs\Foo($bar);
            });
            $league->resolve('foo');
        },
        'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('foo', function () {
                    $bart = new Benchmark\Stubs\Bart;
                    $bam = new Benchmark\Stubs\Bam($bart);
                    $baz = new Benchmark\Stubs\Baz($bam);
                    $bar = new Benchmark\Stubs\Bar($baz);
                    return new Benchmark\Stubs\Foo($bar);
            });
            $illuminate->make('foo');
        },
        'PHP-DI'     => function () {
            $phpdi = new DI\Container;
            $phpdi->useReflection(false);
            $phpdi->useAnnotations(false);
            $phpdi->set('foo', function () {
                    $bart = new Benchmark\Stubs\Bart;
                    $bam = new Benchmark\Stubs\Bam($bart);
                    $baz = new Benchmark\Stubs\Baz($bam);
                    $bar = new Benchmark\Stubs\Bar($baz);
                    return new Benchmark\Stubs\Foo($bar);
            });
            $phpdi->get('foo');
        },
        'Aura'       => function () {
            $aura = new Aura\Di\Container(new Aura\Di\Factory);
            $aura->set('foo', function () {
                    $bart = new Benchmark\Stubs\Bart;
                    $bam = new Benchmark\Stubs\Bam($bart);
                    $baz = new Benchmark\Stubs\Baz($bam);
                    $bar = new Benchmark\Stubs\Bar($baz);
                    return new Benchmark\Stubs\Foo($bar);
            });
            $aura->get('foo');
        },/*
        'Symfony'    => function () {
            $loader  = new Symfony\Component\Routing\Loader\ClosureLoader;
            $loader->load(function () {
                $bart = new Benchmark\Stubs\Bart;
                $bam = new Benchmark\Stubs\Bam($bart);
                $baz = new Benchmark\Stubs\Baz($bam);
                $bar = new Benchmark\Stubs\Bar($baz);
                return new Benchmark\Stubs\Foo($bar);
            });
            $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
            $symfony->register('foo', 'Benchmark\Stubs\Foo');
            $symfony->get('foo');
        },*/
        'Pimple'     => function () {
            $pimple = new Pimple;
            $pimple['foo'] = function () {
                $bart = new Benchmark\Stubs\Bart;
                $bam = new Benchmark\Stubs\Bam($bart);
                $baz = new Benchmark\Stubs\Baz($bam);
                $bar = new Benchmark\Stubs\Bar($baz);
                return new Benchmark\Stubs\Foo($bar);
            };
            $pimple['foo'];
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
            $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\Bart');
            $league->bind('Benchmark\Stubs\Bam')->addArg('Benchmark\Stubs\Bart');
            $league->bind('Benchmark\Stubs\Baz')->addArg('Benchmark\Stubs\Bam');
            $league->bind('Benchmark\Stubs\Bar')->addArg('Benchmark\Stubs\Baz');
            $league->bind('Benchmark\Stubs\Foo')->addArg('Benchmark\Stubs\Bar');
            $league->resolve('Benchmark\Stubs\Foo');
        },/*
        'Illuminate' => function () {
            $illuminate = new Illuminate\Container\Container;
            $illuminate->bind('Foo', 'Benchmark\Stubs\Foo');
            function () {
                return
            }
            $illuminate->bind('Benchmark\Stubs\Bar');
            $illuminate->bind('Benchmark\Stubs\Bam');
            $illuminate->bind('Benchmark\Stubs\BazInterface', 'Benchmark\Stubs\Baz');
            $illuminate->bind('Benchmark\Stubs\BartInterface', 'Benchmark\Stubs\Bart');
            $illuminate->make('foo');
        },*/
        'PHP-DI'     => function () {
            $phpdi = new DI\Container;
            $phpdi->useReflection(false);
            $phpdi->useAnnotations(false);
            $phpdi->addDefinitions(
                array(
                    'Benchmark\Stubs\Bam'  => array(
                        'constructor' => array('Benchmark\Stubs\Bart'),
                    ),
                    'Benchmark\Stubs\Baz'  => array(
                        'constructor' => array('Benchmark\Stubs\Bam'),
                    ),
                    'Benchmark\Stubs\Bar'  => array(
                        'constructor' => array('Benchmark\Stubs\Baz'),
                    ),
                    'Benchmark\Stubs\Foo'  => array(
                        'constructor' => array('Benchmark\Stubs\Bar'),
                    ),
                )
            );
            $phpdi->get('Benchmark\Stubs\Foo');
        },
        'Aura'       => function () {
            $aura = new Aura\Di\Container(new Aura\Di\Factory);
            $aura->params['Benchmark\Stubs\Bam'] = ['bart' => $aura->lazyNew('Benchmark\Stubs\Bart')];
            $aura->params['Benchmark\Stubs\Baz'] = ['bam' => $aura->lazyNew('Benchmark\Stubs\Bam')];
            $aura->params['Benchmark\Stubs\Bar'] = ['baz' => $aura->lazyNew('Benchmark\Stubs\Baz')];
            $aura->params['Benchmark\Stubs\Foo'] = ['bar' => $aura->lazyNew('Benchmark\Stubs\Bar')];
            $aura->newInstance('Benchmark\Stubs\Foo');
        },/*
        'Pimple'     => function () {

        },*/
        'Symfony'    => function () {
            $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
            $symfony->register('foo', 'Benchmark\Stubs\Foo')->addArgument(new Symfony\Component\DependencyInjection\Reference('bar'));
            $symfony->register('bar', 'Benchmark\Stubs\Bar')->addArgument(new Symfony\Component\DependencyInjection\Reference('baz'));
            $symfony->register('baz', 'Benchmark\Stubs\Baz')->addArgument(new Symfony\Component\DependencyInjection\Reference('bam'));
            $symfony->register('bam', 'Benchmark\Stubs\Bam')->addArgument(new Symfony\Component\DependencyInjection\Reference('bart'));
            $symfony->register('bart', 'Benchmark\Stubs\Bart');
            $symfony->get('foo');
        },
        'Zend'       => function () {
            $zend = new Zend\Di\Di;
            $zend->instanceManager()->setInjections('Benchmark\Stubs\Foo', ['Benchmark\Stubs\Bar']);
            $zend->instanceManager()->setInjections('Benchmark\Stubs\Bar', ['Benchmark\Stubs\Baz']);
            $zend->instanceManager()->setInjections('Benchmark\Stubs\Baz', ['Benchmark\Stubs\Bam']);
            $zend->instanceManager()->setInjections('Benchmark\Stubs\Bam', ['Benchmark\Stubs\Bart']);
            $zend->get('Benchmark\Stubs\Foo');
        }
    ),
    'Setter injection with defined setter methods' => array(
        'Orno'       => function () {
            $orno = new Orno\Di\Container;
            $orno->add('Benchmark\Stubs\Bart');
            $orno->add('Benchmark\Stubs\Bam')->withMethodCall('setBart', ['Benchmark\Stubs\Bart']);
            $orno->add('Benchmark\Stubs\Baz')->withMethodCall('setBam', ['Benchmark\Stubs\Bam']);
            $orno->add('Benchmark\Stubs\Bar')->withMethodCall('setBaz', ['Benchmark\Stubs\Baz']);
            $orno->add('Benchmark\Stubs\Foo')->withMethodCall('setBar', ['Benchmark\Stubs\Bar']);
            $orno->get('Benchmark\Stubs\Foo');
        },
        'League'     => function () {
            $league = new League\Di\Container;
            $league->bind('Benchmark\Stubs\Bart');
            $league->bind('Benchmark\Stubs\Bam')->withMethod('setBart', ['Benchmark\Stubs\Bart']);
            $league->bind('Benchmark\Stubs\Baz')->withMethod('setBam', ['Benchmark\Stubs\Bam']);
            $league->bind('Benchmark\Stubs\Bar')->withMethod('setBaz', ['Benchmark\Stubs\Baz']);
            $league->bind('Benchmark\Stubs\Foo')->withMethod('setBar', ['Benchmark\Stubs\Bar']);
            $league->resolve('Benchmark\Stubs\Foo');
        },/*
        'Illuminate' => function () {

        },*/
        'PHP-DI'     => function () {
            $phpdi = new DI\Container;
            $phpdi->useReflection(false);
            $phpdi->useAnnotations(false);
            $phpdi->addDefinitions(
                array(
                    'Benchmark\Stubs\Bart'  => array(
                        'class' => 'Benchmark\Stubs\Bart'
                    ),
                    'Benchmark\Stubs\Bam'  => array(
                        'methods' => array(
                            'setBart' => 'Benchmark\Stubs\Bart',
                        ),
                    ),
                    'Benchmark\Stubs\Baz'  => array(
                        'methods' => array(
                            'setBam' => 'Benchmark\Stubs\Bam',
                        ),
                    ),
                    'Benchmark\Stubs\Bar'  => array(
                        'methods' => array(
                            'setBaz' => 'Benchmark\Stubs\Baz',
                        ),
                    ),
                    'Benchmark\Stubs\Foo'  => array(
                        'methods' => array(
                            'setBar' => 'Benchmark\Stubs\Bar',
                        ),
                    ),
                )
            );
            $phpdi->get('Benchmark\Stubs\Foo');
        },
        'Aura'       => function () {
            $aura = new Aura\Di\Container(new Aura\Di\Factory);
            $aura->setter['Benchmark\Stubs\Bam']['setBart'] = $aura->lazyNew('Benchmark\Stubs\Bart');
            $aura->setter['Benchmark\Stubs\Baz']['setBam'] = $aura->lazyNew('Benchmark\Stubs\Bam');
            $aura->setter['Benchmark\Stubs\Bar']['setBaz'] = $aura->lazyNew('Benchmark\Stubs\Baz');
            $aura->setter['Benchmark\Stubs\Foo']['setBar'] = $aura->lazyNew('Benchmark\Stubs\Bar');
            $aura->newInstance('Benchmark\Stubs\Foo');
        },
        'Symfony'    => function () {
            $symfony = new Symfony\Component\DependencyInjection\ContainerBuilder;
            $symfony->register('foo', 'Benchmark\Stubs\Foo')->addMethodCall('setBar', [new Symfony\Component\DependencyInjection\Reference('bar')]);
            $symfony->register('bar', 'Benchmark\Stubs\Bar')->addMethodCall('setBaz', [new Symfony\Component\DependencyInjection\Reference('baz')]);
            $symfony->register('baz', 'Benchmark\Stubs\Baz')->addMethodCall('setBam', [new Symfony\Component\DependencyInjection\Reference('bam')]);
            $symfony->register('bam', 'Benchmark\Stubs\Bam')->addMethodCall('setBart', [new Symfony\Component\DependencyInjection\Reference('bart')]);
            $symfony->register('bart', 'Benchmark\Stubs\Bart');
            $symfony->get('foo');
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
            $zend->get('Benchmark\Stubs\Foo');
        }
    )
);
$included   = function ($name) {
    static $init;
    $filter = function ($file) use ($name) {
        return stristr($file, $name);
    };
    $init = ! $init;
    return $init ? 0 : count(array_filter(get_included_files(), $filter));
};
$graph_json = function (&$benchmark) use ($measure, $included) {
    $sort = $results = array();
    foreach ($benchmark as $name => $test) {
        $files     = $measure->benchmarkCustom($included, $test, array($name));
        $memory    = $measure->benchmarkMemory($test);
        $time      = $measure->benchmarkTime($test, [], 100);
        $sort[]    = $time[Measure::BENCHMARK_AVERAGE];
        unset($test);
        $benchmark[$name] = null;
        $results[] = array(
            $name,
            $time[Measure::BENCHMARK_AVERAGE] * 1000,
            $memory[Measure::MEMORY_VALUE] / 1024,
            $files[Measure::BENCHMARK_VALUE],
            gc_collect_cycles()
        );
    }
    array_multisort($sort, $results);
    array_unshift($results, array(
            'Component',
            'Time per test, average in µs',
            'Memory usage for one test in kb',
            'Files included',
            'Cycle collected after ' . $time[Measure::BENCHMARK_COUNT] . ' tests'
        ));
    return implode(",\n                ", array_map('json_encode', $results)) . "\n";
};
$i          = 0;
gc_disable();
foreach ($benchmarks as $title => &$benchmark) :  ?>
    <h1>Benchmark <?= ++$i . ' : ' . $title; ?>.</h1>
    <div id="benchmark<?= $i; ?>"></div>
    <script type="text/javascript">
        google.setOnLoadCallback( function () {
            var data = google.visualization.arrayToDataTable([
                <?php echo $graph_json($benchmark); ?>
            ]);
            var chart = new google.visualization.ColumnChart(document.getElementById('benchmark<?= $i; ?>'));
            chart.draw(data, options);
        });
    </script>
    <?php
echo 'Memory peak : ', memory_get_peak_usage(true) / 1024 / 1024, 'Mb';
endforeach;
?>
</body>
</html>
