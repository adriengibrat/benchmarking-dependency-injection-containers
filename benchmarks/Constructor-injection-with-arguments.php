<?php
return array(
    'Orno'       => function () {
        $orno = new Orno\Di\Container;
        $orno->add('Benchmark\Stubs\Bart');
        $orno->add('Benchmark\Stubs\Bam')->withArgument('Benchmark\Stubs\Bart');
        $orno->add('Benchmark\Stubs\Baz')->withArgument('Benchmark\Stubs\Bam');
        $orno->add('Benchmark\Stubs\Bar')->withArgument('Benchmark\Stubs\Baz');
        $orno->add('Benchmark\Stubs\Foo')->withArgument('Benchmark\Stubs\Bar');
        return $orno->get('Benchmark\Stubs\Foo');
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
        $zend->instanceManager()->setParameters('Benchmark\Stubs\Foo', ['bar' => 'Benchmark\Stubs\Bar']);
        $zend->instanceManager()->setParameters('Benchmark\Stubs\Bar', ['baz' => 'Benchmark\Stubs\Baz']);
        $zend->instanceManager()->setParameters('Benchmark\Stubs\Baz', ['bam' => 'Benchmark\Stubs\Bam']);
        $zend->instanceManager()->setParameters('Benchmark\Stubs\Bam', ['bart' => 'Benchmark\Stubs\Bart']);
        return $zend->get('Benchmark\Stubs\Foo');
    }
);
