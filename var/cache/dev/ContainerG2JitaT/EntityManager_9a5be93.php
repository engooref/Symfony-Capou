<?php

namespace ContainerG2JitaT;
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'persistence'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'Persistence'.\DIRECTORY_SEPARATOR.'ObjectManager.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'doctrine'.\DIRECTORY_SEPARATOR.'orm'.\DIRECTORY_SEPARATOR.'lib'.\DIRECTORY_SEPARATOR.'Doctrine'.\DIRECTORY_SEPARATOR.'ORM'.\DIRECTORY_SEPARATOR.'EntityManager.php';

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager|null wrapped object, if the proxy is initialized
     */
    private $valueHoldere6087 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializereac4e = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicPropertiesa99e1 = [
        
    ];

    public function getConnection()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getConnection', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getConnection();
    }

    public function getMetadataFactory()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getMetadataFactory', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getMetadataFactory();
    }

    public function getExpressionBuilder()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getExpressionBuilder', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getExpressionBuilder();
    }

    public function beginTransaction()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'beginTransaction', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->beginTransaction();
    }

    public function getCache()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getCache', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getCache();
    }

    public function transactional($func)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'transactional', array('func' => $func), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->transactional($func);
    }

    public function commit()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'commit', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->commit();
    }

    public function rollback()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'rollback', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->rollback();
    }

    public function getClassMetadata($className)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getClassMetadata', array('className' => $className), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getClassMetadata($className);
    }

    public function createQuery($dql = '')
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'createQuery', array('dql' => $dql), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'createNamedQuery', array('name' => $name), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->createNamedQuery($name);
    }

    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'createQueryBuilder', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->createQueryBuilder();
    }

    public function flush($entity = null)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'flush', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->flush($entity);
    }

    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->find($className, $id, $lockMode, $lockVersion);
    }

    public function getReference($entityName, $id)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getPartialReference($entityName, $identifier);
    }

    public function clear($entityName = null)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'clear', array('entityName' => $entityName), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->clear($entityName);
    }

    public function close()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'close', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->close();
    }

    public function persist($entity)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'persist', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->persist($entity);
    }

    public function remove($entity)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'remove', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->remove($entity);
    }

    public function refresh($entity)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'refresh', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->refresh($entity);
    }

    public function detach($entity)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'detach', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->detach($entity);
    }

    public function merge($entity)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'merge', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->merge($entity);
    }

    public function copy($entity, $deep = false)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->lock($entity, $lockMode, $lockVersion);
    }

    public function getRepository($entityName)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getRepository', array('entityName' => $entityName), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getRepository($entityName);
    }

    public function contains($entity)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'contains', array('entity' => $entity), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->contains($entity);
    }

    public function getEventManager()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getEventManager', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getEventManager();
    }

    public function getConfiguration()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getConfiguration', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getConfiguration();
    }

    public function isOpen()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'isOpen', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->isOpen();
    }

    public function getUnitOfWork()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getUnitOfWork', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getProxyFactory', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getProxyFactory();
    }

    public function initializeObject($obj)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'initializeObject', array('obj' => $obj), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->initializeObject($obj);
    }

    public function getFilters()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'getFilters', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->getFilters();
    }

    public function isFiltersStateClean()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'isFiltersStateClean', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->isFiltersStateClean();
    }

    public function hasFilters()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'hasFilters', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return $this->valueHoldere6087->hasFilters();
    }

    /**
     * Constructor for lazy initialization
     *
     * @param \Closure|null $initializer
     */
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;

        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();

        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);

        $instance->initializereac4e = $initializer;

        return $instance;
    }

    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;

        if (! $this->valueHoldere6087) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHoldere6087 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);

        }

        $this->valueHoldere6087->__construct($conn, $config, $eventManager);
    }

    public function & __get($name)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, '__get', ['name' => $name], $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        if (isset(self::$publicPropertiesa99e1[$name])) {
            return $this->valueHoldere6087->$name;
        }

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere6087;

            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldere6087;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __set($name, $value)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, '__set', array('name' => $name, 'value' => $value), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere6087;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldere6087;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;

            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __isset($name)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, '__isset', array('name' => $name), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere6087;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHoldere6087;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    public function __unset($name)
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, '__unset', array('name' => $name), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldere6087;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHoldere6087;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);

            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }

    public function __clone()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, '__clone', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        $this->valueHoldere6087 = clone $this->valueHoldere6087;
    }

    public function __sleep()
    {
        $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, '__sleep', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;

        return array('valueHoldere6087');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializereac4e = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializereac4e;
    }

    public function initializeProxy() : bool
    {
        return $this->initializereac4e && ($this->initializereac4e->__invoke($valueHoldere6087, $this, 'initializeProxy', array(), $this->initializereac4e) || 1) && $this->valueHoldere6087 = $valueHoldere6087;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHoldere6087;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHoldere6087;
    }
}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
