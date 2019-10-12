<?php
/**
 * The control file of client module of XXB.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     client
 * @version     $Id$
 * @link        http://xuan.im
 */
class client extends control
{
    /**
     * Index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $blocks = $this->loadModel('block')->getBlockList('xxb');

        /* Init block when vist index first. */
        if(empty($blocks))
        {   
            if($this->loadModel('block')->initBlock('xxb')) die(js::reload());
        }

        foreach($blocks as $key => $block)
        {
            $block->params = json_decode($block->params);
            if(empty($block->params)) $block->params = new stdclass();

            $sign = $this->config->requestType == 'PATH_INFO' ? '?' : '&';
            $block->blockLink = $this->createLink('block', 'print' . $block->block . 'block', "index={$block->id}");
        }

        $this->view->title  = $this->lang->client->common;
        $this->view->blocks = $blocks;
        $this->view->notice = $this->client->getNotice();
        $this->display();
    }

    /**
     * Browse client list.
     *
     * @access public
     * @return void
     */
    public function browse()
    {
        $this->view->title   = $this->lang->client->update;
        $this->view->clients = $this->client->getList();
        $this->display();
    }

    /**
     * Create a client.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->client->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->client->create;
        $this->display();
    }

    /**
     * Download remote package.
     * @param string $version
     * @param string $link
     * @param string $os
     * @return string
     */
    public function download($version = '', $link = '', $os = '')
    {
        set_time_limit(0);
        $result = $this->client->downloadZipPackage($version, $link);
        if($result == false) $this->send(array('result' => 'fail', 'message' => $this->lang->client->downloadFail));
        $client = $this->client->edit($version, $result, $os);
        if($client == false) $this->send(array('result' => 'fail', 'message' => $this->lang->client->saveClientError));
        $this->send(array('result' => 'success', 'client' => $client, 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
    }

    /**
     * Edit a version.
     *
     * @param  int    $clientID
     * @access public
     * @return void
     */
    public function edit($clientID)
    {
        if($_POST)
        {
            $this->client->update($clientID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title  = $this->lang->client->edit;
        $this->view->client = $this->client->getByID($clientID);
        $this->display();
    }

    /**
     * View changelog of a client update
     *
     * @param  int    $clientID
     * @access public
     * @return void
     */
    public function changelog($clientID)
    {
        $client             = $this->client->getByID($clientID);
        $this->view->client = $client;
        $this->view->title  = $this->lang->client->changeLog . ' ' . $client->version;
        $this->display();
    }

    /**
     * Delete a client.
     *
     * @param  int    $clientID
     * @access public
     * @return void
     */
    public function delete($clientID)
    {
        $this->dao->delete()->from(TABLE_IM_CLIENT)->where('id')->eq($clientID)->exec();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Check upgrade.
     *
     * @access public
     * @return void
     */
    public function checkUpgrade()
    {
        $currentVersion = $this->client->getCurrentVerson();
        $apiUrl         = str_replace('$', $currentVersion ? $currentVersion->version : '', $this->config->client->upgradeApi);
        $jsonData       = file_get_contents($apiUrl);

        $serverVersions = json_decode($jsonData, false);

        $this->view->title          = $this->lang->client->checkUpgrade;
        $this->view->serverVersions = $serverVersions;
        $this->view->versions       = $serverVersions;
        $this->view->currentVersion = $currentVersion;
        $this->view->path           = $this->app->dataRoot; 
        $this->display();
    }
}
