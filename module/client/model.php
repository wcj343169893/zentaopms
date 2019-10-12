<?php
/**
 * The model file of client module of XXB.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     client
 * @version     $Id$
 * @link        http://xuan.im
 */
class clientModel extends model
{
    /**
     * Get a client by id.
     *
     * @param  int    $clientID
     * @access public
     * @return object | bool
     */
    public function getByID($clientID)
    {
        $client = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('id')->eq($clientID)->fetch();
        if(empty($client)) return false;
        $client->downloads = json_decode($client->downloads, true);
        return $client;
    }

    /**
     * Get a client by version.
     *
     * @param  string $version
     * @access public
     * @return object | bool
     */
    public function getByVersion($version)
    {
        $client = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('version')->eq($version)->fetch();
        if(empty($client)) return false;
        $client->downloads = json_decode($client->downloads, true);
        return $client;
    }

    /**
     * Get client list.
     *
     * @access public
     * @return array
     */
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_IM_CLIENT)->orderBy('id_desc')->fetchAll();
    }

    /**
     * Get xxc current version from xuan.im.
     *
     * @access public
     * @return array
     */
    public function getXxcVersionFromWebsite()
    {
        $this->loadModel('setting');
        $now           = helper::now();
        $xxcUpdateInfo = $this->setting->getItem('owner=system&module=common&section=client&key=xxcUpdateInfo');
        if(!empty($xxcUpdateInfo))
        {
            $xxcInfo     = json_decode($xxcUpdateInfo, false);
            $prevGetDate = $xxcInfo->updateDate;
            if(!empty($prevGetDate) && helper::diffDate($now, $prevGetDate) <= 3)
            {
                if(!empty($xxcInfo->version)) return $xxcInfo->version;
            }
        }

        $currentVersion = $this->getCurrentVerson();
        $apiUrl         = str_replace('$', $currentVersion ? $currentVersion->version : '', $this->config->client->upgradeApi);
        $jsonData       = file_get_contents($apiUrl);
        $serverVersions = json_decode($jsonData, false);

        if(empty($serverVersions)) return false;
        $xxcVersion = $serverVersions[0]->xxcVersion;

        if(!empty($xxcVersion))
        {
            $xxcUpdateInfo             = new stdclass();
            $xxcUpdateInfo->version    = $xxcVersion;
            $xxcUpdateInfo->updateDate = $now;

            $value = json_encode($xxcUpdateInfo);
            $this->setting->setItem('system.common.client.xxcUpdateInfo', $value);
        }
         
        return $xxcVersion;
    }

    /**
     * Create a client.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $client = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        if(empty($client->version)) dao::$errors['version'][] = sprintf($this->lang->error->notempty, $this->lang->client->version);
        if($client->version && !preg_match("/^[0-9.]*$/", $client->version)) dao::$errors['version'][] = $this->lang->client->wrongVersion;
        foreach($client->downloads as $os => $url)
        {
            if(!empty($url) && !validater::checkURL($url)) dao::$errors[$os][] = sprintf($this->lang->error->URL, zget($this->lang->client->zipList, $os) . $this->lang->client->downloadLink);
        }
        if(dao::isError()) return false;

        $client->downloads = helper::jsonEncode($client->downloads);
        $this->dao->insert(TABLE_IM_CLIENT)->data($client)->autoCheck()->exec();

        return !dao::isError();
    }

    /**
     * Create or edit a client by account.
     * @param $version
     * @param $link
     * @param $os
     * @return bool
     */
    public function edit($version, $link, $os)
    {
        $client = $this->getByVersion($version);
        if($client)
        {
            $client->downloads[$os] = $link;
            $client->editedBy       = $this->app->user->account;
            $client->editedDate     = helper::now();
            $client->downloads      = helper::jsonEncode( $client->downloads);
            $this->dao->update(TABLE_IM_CLIENT)->data($client)->where('id')->eq($client->id)->exec();
        }
        else
        {
            $client = new stdClass();
            $client->status      = 'wait';
            $client->version     = $version;
            $client->strategy    = 'optional';
            $client->downloads   = helper::jsonEncode(array($os => $link));
            $client->createdBy   = $this->app->user->account;
            $client->createdDate = helper::now();
            $this->dao->insert(TABLE_IM_CLIENT)->data($client)->autoCheck()->exec();

            $client->id = $this->dao->lastInsertID();
        }
        if(dao::isError()) return false;
        return $client;
    }

    /**
     * Update a client.
     *
     * @param  int    $clientID
     * @access public
     * @return bool
     */
    public function update($clientID)
    {
        $client = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        if(empty($client->version)) dao::$errors['version'][] = sprintf($this->lang->error->notempty, $this->lang->client->version);
        if($client->version && !preg_match("/^[0-9.]*$/", $client->version)) dao::$errors['version'][] = $this->lang->client->wrongVersion;
        foreach($client->downloads as $os => $url)
        {
            if(!empty($url) && !validater::checkURL($url)) dao::$errors[$os][] = sprintf($this->lang->error->URL, zget($this->lang->client->zipList, $os) . $this->lang->client->downloadLink);
        }
        if(dao::isError()) return false;

        $client->downloads = helper::jsonEncode($client->downloads);
        $this->dao->update(TABLE_IM_CLIENT)->data($client)->autoCheck()->where('id')->eq($clientID)->exec();

        return !dao::isError();
    }

    /**
     * Get current version
     * @access public
     * @return object | bool
     */
    public function getCurrentVerson()
    {
        $currentVersion = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('status')->eq('released')->orderBy('id_desc')->limit(1)->fetch();

        if(dao::isError()) return false;
        return $currentVersion;
    }

    /**
     * Check if a client need upgrade.
     *
     * @param  string $version
     * @access public
     * @return object | bool
     */
    public function checkUpgrade($version)
    {
        $lastForce = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('strategy')->eq('force')->andWhere('status')->eq('released')->orderBy('id_desc')->limit(1)->fetch();
        if($lastForce && version_compare($version, $lastForce->version) == -1)
        {
            return $lastForce;
        }
        else
        {
            $last = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('strategy')->eq('optional')->andWhere('status')->eq('released')->orderBy('id_desc')->limit(1)->fetch();
            if($last && version_compare($version, $last->version) == -1)
            {
                return $last;
            }
        }
        return false;
    }

    /**
     * Download zip package.
     * @param $version
     * @param $link
     * @return bool | string
     */
    public function downloadZipPackage($version, $link)
    {
        ignore_user_abort(true);
        set_time_limit(0);
        if(empty($version) || empty($link)) return false;
        $dir  = "data/client/" . $version . '/';
        $link = helper::safe64Decode($link);
        $file = basename($link);
        if(!is_dir($this->app->wwwRoot . $dir))
        {
            mkdir($this->app->wwwRoot . $dir, 0755, true);
        }
        if(!is_dir($this->app->wwwRoot . $dir)) return false;
        if(file_exists($this->app->wwwRoot . $dir . $file))
        {
            return commonModel::getSysURL() . $this->config->webRoot . $dir . $file;
        }
        ob_clean();
        ob_end_flush();

        $local  = fopen($this->app->wwwRoot . $dir . $file, 'w');
        $remote = fopen($link, 'rb');
        if($remote === false) return false;
        while(!feof($remote))
        {
            $buffer = fread($remote, 4096);
            fwrite($local, $buffer);
        }
        fclose($local);
        fclose($remote);
        return commonModel::getSysURL() . $this->config->webRoot . $dir . $file;
    }

    /**
     * Get notice content. 
     * 
     * @access public
     * @return string
     */
    public function getNotice()
    {
        $currentVersion = $this->getCurrentVerson(); 
        $xxcVersion     = $this->getXxcVersionFromWebsite();

        if(!empty($currentVersion) && version_compare($currentVersion->version, $xxcVersion, '>=')) return ''; 

        $updateLink = html::a(helper::createLink('client', 'checkUpgrade'), $this->lang->client->goUpdate);

        $notice  = '';
        $notice .= '<div id="noticeGoUpdate" class="alert alert-success with-icon alert-dismissable" style="width:380px; position:fixed; bottom:25px; right:15px; z-index: 9999;">';
        $notice .= '<i class="icon icon-cog"></i>';
        $notice .= '<div class="content">';
        $notice .= "<p>{$this->lang->client->xxcNotice}<small class='text-danger'>({$xxcVersion})，</small>{$updateLink}。</p>";
        $notice .= '</div>';
        $notice .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button';
        $notice .= '</div>';

        return !empty($xxcVersion) ? $notice : '';
    }
}
