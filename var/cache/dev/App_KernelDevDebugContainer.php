<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerSDxfDr5\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerSDxfDr5/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerSDxfDr5.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerSDxfDr5\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerSDxfDr5\App_KernelDevDebugContainer([
    'container.build_hash' => 'SDxfDr5',
    'container.build_id' => '6fdf22e6',
    'container.build_time' => 1616405195,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerSDxfDr5');
