<?php

/**
 * Prepare the test setup.
 */
namespace Horde\Controller;
use Horde_Test_Case as TestCase;
use \Horde_Controller_PreFilter;
use \Horde_Controller_FilterRunner;
use \Horde_Controller_Response;
use \Horde_Controller_Request_Null;

class FilterRunnerTest extends TestCase
{
    public function testFilterRunnerDoesNotCallControllerWhenAPreFilterHandlesTheRequest()
    {
        $filter = $this->getMockBuilder('Horde_Controller_PreFilter')->setMethods(array('processRequest'))->getMock();
        $filter->expects($this->once())
            ->method('processRequest')
            ->will($this->returnValue(Horde_Controller_PreFilter::REQUEST_HANDLED));

        $runner = new Horde_Controller_FilterRunner($this->_getControllerMockNeverCalled());
        $runner->addPreFilter($filter);
        $runner->processRequest($this->getMockBuilder('Horde_Controller_Request')->getMock(), new Horde_Controller_Response());
    }

    public function testShouldUsePreFiltersInFirstInFirstOutOrder()
    {
        // The second filter should never be called because first filter returns
        // REQUEST_HANDLED, meaning it can handle the request.
        $preFilter1 = $this->getMockBuilder('Horde_Controller_PreFilter')->setMethods(array('processRequest'))->getMock();
        $preFilter1->expects($this->once())
            ->method('processRequest')
            ->will($this->returnValue(Horde_Controller_PreFilter::REQUEST_HANDLED));

        $preFilter2 = $this->getMockBuilder('Horde_Controller_PreFilter')->setMethods(array('processRequest'))->getMock();
        $preFilter2->expects($this->never())
            ->method('processRequest');

        $runner = new Horde_Controller_FilterRunner($this->_getControllerMockNeverCalled());
        $runner->addPreFilter($preFilter1);
        $runner->addPreFilter($preFilter2);
        $this->_runFilterRunner($runner);
    }

    public function testShouldUsePostFiltersInFirstInLastOutOrder()
    {
        // Both filters should be called because the first filter returns
        // REQUEST_HANDLED, meaning it can handle the request
        $postFilter1 = $this->getMockBuilder('Horde_Controller_PostFilter')->setMethods(array('processResponse'))->getMock();
        $postFilter1->expects($this->once())
            ->method('processResponse')
            ->will($this->returnValue(Horde_Controller_PreFilter::REQUEST_HANDLED));

        $postFilter2 = $this->getMockBuilder('Horde_Controller_PostFilter')->setMethods(array('processResponse'))->getMock();
        $postFilter2->expects($this->once())
            ->method('processResponse');


        $controller = $this->getMockBuilder('Horde_Controller')->setMethods(array('processRequest'))->getMock();
        $controller->expects($this->once())
            ->method('processRequest');

        $runner = new Horde_Controller_FilterRunner($controller);
        $runner->addPostFilter($postFilter1);
        $runner->addPostFilter($postFilter2);
        $this->_runFilterRunner($runner);
    }

    private function _getControllerMockNeverCalled()
    {
        $controller = $this->getMockBuilder('Horde_Controller')->setMethods(array('processRequest'))->getMock();
        $controller->expects($this->never())
            ->method('processRequest');
        return $controller;
    }

    private function _runFilterRunner(Horde_Controller_FilterRunner $runner)
    {
        $response = $this->getMockBuilder('Horde_Controller_Response')->setMethods(array('processRequest'))->getMock();
        $response->expects($this->never())->method('processRequest');
        $runner->processRequest(new Horde_Controller_Request_Null(), $response);
    }
}
