<?php
return array(
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
);
