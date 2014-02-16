<?php
return array(
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
);
