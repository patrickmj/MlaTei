<?php

class MlaTei_SearchController extends Omeka_Controller_Action
{
    protected $_browseRecordsPerPage = 10;

    public function init()
    {
        if (version_compare(OMEKA_VERSION, '2.0-dev', '>=')) {
            $this->_helper->db->setDefaultModelName('MlaTeiElement_CommentaryNote');
        } else {
            $this->_modelClass = 'MlaTeiElement_CommentaryNote';
        }
    }    
    
    public function commentaryAction()
    {
        $search = $this->_getParam('search');
        $count = $this->getTable()->count(array('search'=>$search));
        $page = $this->_getParam('page');
        
        /**
         * Now process the pagination
         *
        */
        $paginationUrl = $this->getRequest()->getBaseUrl().'/mla-tei/search/commentary/search/' . $search;
        
        //Serve up the pagination
        $pagination = array('menu'          => null, // This hasn't done anything since $menu was never instantiated in ItemsController::browseAction()
                'page'          => $page,
                'per_page'      => 10,
                'total_results' => $count,
                'link'          => $paginationUrl);
        
        Zend_Registry::set('pagination', $pagination);
        
        
        $discussions = $this->getTable()->findBy(array('search'=>$search, 'sort_field'=>'id'), 10, $page);
        $this->view->discussions = $discussions;
        $this->view->search = $search;
        $this->view->count = $count;
    }
    
    public function appendixAction()
    {
        $search = $this->_getParam('search');
        $count = $this->getTable('MlaTeiElement_AppendixP')->count(array('search'=>$search));
        $page = $this->_getParam('page');

        /**
         * Now process the pagination
         *
         */
        $paginationUrl = $this->getRequest()->getBaseUrl().'/mla-tei/search/appendix/search/' . $search;
        
        //Serve up the pagination
        $pagination = array('menu'          => null, // This hasn't done anything since $menu was never instantiated in ItemsController::browseAction()
                'page'          => $page,
                'per_page'      => 10,
                'total_results' => $count,
                'link'          => $paginationUrl);
        
        Zend_Registry::set('pagination', $pagination);
        
        
        
        $discussions = $this->getTable('MlaTeiElement_AppendixP')->findBy(array('search'=>$search), 10, $page);
        $this->view->discussions = $discussions;
        $this->view->search = $search;
        $this->view->count = $count;
    }    
    
}