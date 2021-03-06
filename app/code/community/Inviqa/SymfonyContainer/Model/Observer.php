<?php

use Inviqa_SymfonyContainer_Model_ConfigurationBuilder as ConfigurationBuilder;
use Inviqa_SymfonyContainer_Helper_ContainerProvider as ContainerProvider;

class Inviqa_SymfonyContainer_Model_Observer
{
    use Inviqa_SymfonyContainer_Helper_ServiceProvider;

    const SERVICE_INJECTOR = 'inviqa_symfonyContainer/serviceInjector';

    public function onCacheRefresh(Varien_Event_Observer $event)
    {
        if (ConfigurationBuilder::MODEL_ALIAS === $event->getType()) {
            unlink(Mage::getBaseDir('cache') . '/' . ConfigurationBuilder::CACHED_CONTAINER);
        }
    }
    public function onPreDispatch(Varien_Event_Observer $event)
    {
        $controller = $event->getControllerAction();

        Mage::getSingleton(self::SERVICE_INJECTOR, [
            'container' => Mage::helper(ContainerProvider::HELPER_NAME)->getContainer()
        ])->setupDependencies($controller);
    }
}
