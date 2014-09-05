<?php
return array(
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
    },
    'Aura'       => function () {
        $aura = new Aura\Di\Container(new Aura\Di\Factory);
        $aura->types['Benchmark\Stubs\Foo'] = $aura->lazyNew('Benchmark\Stubs\Foo');
        $aura->types['Benchmark\Stubs\Bar'] = $aura->lazyNew('Benchmark\Stubs\Bar');
        $aura->types['Benchmark\Stubs\Bam'] = $aura->lazyNew('Benchmark\Stubs\Bam');
        $aura->types['Benchmark\Stubs\BazInterface'] = $aura->lazyNew('Benchmark\Stubs\Baz');
        $aura->types['Benchmark\Stubs\BartInterface'] = $aura->lazyNew('Benchmark\Stubs\Bart');
        return $aura->newInstance('Benchmark\Stubs\Foo');
    },
);
