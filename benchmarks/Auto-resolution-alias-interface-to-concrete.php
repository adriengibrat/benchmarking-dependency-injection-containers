<?php
return array(
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
);
