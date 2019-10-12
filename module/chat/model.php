<?php
class chatModel extends model
{
    /**
     * Reset user status.
     *
     * @param  string $status
     * @access public
     * @return bool
     */
    public function resetUserStatus($status = 'offline')
    {
        $this->dao->update(TABLE_USER)->set('clientStatus')->eq($status)->exec();
        return !dao::isError();
    }

    /**
     * Create a system chat.
     *
     * @access public
     * @return bool
     */
    public function createSystemChat()
    {
        $chat = $this->dao->select('*')->from(TABLE_IM_CHAT)->where('type')->eq('system')->fetch();
        if(!$chat)
        {
            $chat = new stdclass();
            $chat->gid         = $this->createGID();
            $chat->name        = $this->lang->chat->systemGroup;
            $chat->type        = 'system';
            $chat->createdBy   = 'system';
            $chat->createdDate = helper::now();

            $this->dao->insert(TABLE_IM_CHAT)->data($chat)->exec();
        }
        return !dao::isError();
    }

    /**
     * Get signed time.
     * Other program can extend this function.
     *
     * @param  string $account
     * @access public
     * @return string | int
     */
    public function getSignedTime($account = '')
    {
        return time();
    }

    /**
     * Format users.
     *
     * @param  mixed  $users  object | array
     * @access public
     * @return object | array
     */
    public function formatUsers($users)
    {
        $isObject = false;
        if(is_object($users))
        {
            $isObject = true;
            $users    = array($users);
        }

        foreach($users as $user)
        {
            $user->id     = (int)$user->id;
            $user->dept   = (int)$user->dept;
            $user->avatar = !empty($user->avatar) ? commonModel::getSysURL() . $user->avatar : $user->avatar;
            $user->status = $user->clientStatus;
            if(isset($user->deleted)) $user->deleted = (int)$user->deleted;
        }

        if($isObject) return reset($users);

        return $users;
    }

    /**
     * Get a user.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getUserByUserID($userID = 0)
    {
		$user = $this->dao->select('id, account, realname, avatar, role, dept, clientStatus, gender, email, mobile, phone,  qq, deleted')
			->from(TABLE_USER)
			->where('id')->eq($userID)
			->fetch();
        if(!$user) return array();

        return $this->formatUsers($user);
    }

    public function getGidByUserID($userID = 0)
    {
        return $this->dao->select('t1.gid')->from(TABLE_IM_CHAT)->alias('t1')
            ->leftJoin(TABLE_IM_CHATUSER)->alias('t2')->on('t2.cgid=t1.gid')
            ->where('t2.user')->eq($userID)
            ->orWhere('t1.type')->eq('system')
            ->fetchPairs('gid');
    }
    
    /**
     * Get user list.
     *
     * @param  string $status
     * @param  array  $idList
     * @param  bool   $idAsKey
     * @access public
     * @return array
     */
    public function getUserList($status = '', $idList = array(), $idAsKey = true)
    {
        $dao = $this->dao->select('id, account, realname, avatar, role, dept, clientStatus, gender, email, mobile, phone,  qq, deleted')
            ->from(TABLE_USER)
            ->where(1)
            ->beginIF(empty($idList))
            ->andWhere('deleted')->eq('0')
            ->fi()
            ->beginIF($status && $status == 'online')->andWhere('clientStatus')->ne('offline')->fi()
            ->beginIF($status && $status != 'online')->andWhere('clientStatus')->eq($status)->fi()
            ->beginIF($idList)->andWhere('id')->in($idList)->fi();

        $users = $idAsKey ? $dao->fetchAll('id') : $dao->fetchAll();

        return $this->formatUsers($users);
    }

    /**
     * Get output data of user list.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getUserListOutput($idList = array(), $userID)
    {
        $output = new stdclass();
        $output->module = 'chat';
        $output->method = 'userGetList';

        $users = $this->getUserList($status = '', $idList, $idAsKey = false);
        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Get userlist failed.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = !empty($userID) ? array($userID) : array();
            $output->data   = $users;

            if(empty($idList))
            {
                $this->app->loadLang('user');
                $roles = $this->lang->user->roleList;

                $allDepts = $this->loadModel('dept')->getListByType('dept');
                $depts = array();
                foreach($allDepts as $id => $dept)
                {
                    $depts[$id] = array('name' => $dept->name, 'order' => (int)$dept->order, 'parent' => (int)$dept->parent);
                }
                $output->roles = $roles;
                $output->depts = $depts;
            }
            else
            {
                $output->partial = $idList;
            }
        }
        return $output;
    }

    /**
     * Edit a user.
     *
     * @param  object $user
     * @access public
     * @return object
     */
    public function editUser($user = null)
    {
        if(empty($user->id)) return null;

        $data = array();
        foreach($this->config->chat->user->canEditFields as $field)
        {
            if(!empty($user->$field)) $data[$field] = $user->$field;
        }
        if(!empty($user->account) && !empty($user->password)) $data['password'] = md5($user->password . $user->account);
        if(!$data) return null;

        $data['clientLang'] = $this->session->clientLang;
        $this->dao->update(TABLE_USER)->data($data)->where('id')->eq($user->id)->exec();
        return $this->getUserByUserID($user->id);
    }

    /**
     * Get member list by gid.
     *
     * @param  string $gid
     * @access public
     * @return array
     */
    public function getMemberListByGID($gid = '')
    {
        $chat = $this->getByGID($gid);
        if(!$chat) return array();

        if($chat->type == 'system')
        {
            $memberList = $this->dao->select('id')->from(TABLE_USER)->where('deleted')->eq('0')->fetchPairs();
        }
        else
        {
            $memberList = $this->dao->select('user')
                ->from(TABLE_IM_CHATUSER)
                ->where('quit')->eq('0000-00-00 00:00:00')
                ->beginIF($gid)->andWhere('cgid')->eq($gid)->fi()
                ->fetchPairs();
        }

        $members = array();
        foreach($memberList as $member) $members[] = (int)$member;

        return $members;
    }

    /**
     * Get message list.
     *
     * @param  array   $idList
     * @param  object  $pager
     * @param  string  $startDate
     * @param  int     $userID
     * @access public
     * @return array
     */
    public function getMessageList($idList = array(), $pager = null, $startDate = '', $userID = null)
    {
        $messages = $this->dao->select('*')
            ->from(TABLE_IM_MESSAGE)
            ->where('1')
            ->beginIF($idList)->andWhere('id')->in($idList)->fi()
            ->beginIF($startDate)->andWhere('date')->ge($startDate)->fi()
            ->beginIF($userID != null)->andWhere('user')->eq($userID)->fi()
            ->orderBy('id_desc')
            ->beginIF($pager != null)->page($pager)->fi()
            ->fetchAll();

        foreach($messages as $message)
        {
            $message->id    = (int)$message->id;
            $message->order = (int)$message->order;
            $message->user  = (int)$message->user;
            $message->date  = strtotime($message->date);
        }

        return $messages;
    }

    /**
     * Get message list by cgid.
     *
     * @param  string $cgid
     * @param  object $pager
     * @param  string $startDate
     * @access public
     * @return array
     */
    public function getMessageListByCGID($cgid = '', $pager = null, $startDate = '')
    {
        $messages = $this->dao->select('*')->from(TABLE_IM_MESSAGE)
            ->where('cgid')->eq($cgid)
            ->beginIF($startDate)->andWhere('date')->ge($startDate)->fi()
            ->orderBy('id_desc')
            ->beginIF($pager != null)->page($pager)->fi()
            ->fetchAll();

        foreach($messages as $message)
        {
            $message->id    = (int)$message->id;
            $message->order = (int)$message->order;
            $message->user  = (int)$message->user;
            $message->date  = strtotime($message->date);
        }

        return $messages;
    }

    /**
     * Get message number by time frame.
     *
     * @param  string $date
     * @access public
     * @return array
     */
    public function getMessageNumByTimeFrame()
    {
        $messages = $this->dao->select('date')->from(TABLE_IM_MESSAGE)
            ->where('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchAll();

        $hourNum  = 0;
        $dayNum   = 0;
        $totalNum = count($messages); 
        $hourDate = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $dayDate  = date('Y-m-d H:i:s', strtotime('-1 day'));
        foreach($messages as $message)
        {
            if(strtotime($message->date) > strtotime($hourDate)) $hourNum++;
            if(strtotime($message->date) > strtotime($dayDate))  $dayNum++;
        }

        $messagesCount = new stdClass();
        $messagesCount->total = $totalNum;
        $messagesCount->hour  = $hourNum;
        $messagesCount->day   = $dayNum;
        
        return $messagesCount;
    }

    /**
     * Foramt chats.
     *
     * @param  mixed  $chats  object | array
     * @access public
     * @return object | array
     */
    public function formatChats($chats)
    {
        $isObject = false;
        if(is_object($chats))
        {
            $isObject = true;
            $chats    = array($chats);
        }

        foreach($chats as $chat)
        {
            $chat->id             = (int)$chat->id;
            $chat->subject        = (int)$chat->subject;
            $chat->public         = (int)$chat->public;
            $chat->createdDate    = strtotime($chat->createdDate);
            $chat->editedDate     = $chat->editedDate == '0000-00-00 00:00:00' ? 0 : strtotime($chat->editedDate);
            $chat->lastActiveTime = $chat->lastActiveTime == '0000-00-00 00:00:00' ? 0 : strtotime($chat->lastActiveTime);
            $chat->dismissDate    = $chat->dismissDate == '0000-00-00 00:00:00' ? 0 : strtotime($chat->dismissDate);

            if($chat->type == 'one2one') $chat->name = '';

            if(isset($chat->star)) $chat->star = (int)$chat->star;
            if(isset($chat->hide)) $chat->hide = (int)$chat->hide;
            if(isset($chat->mute)) $chat->mute = (int)$chat->mute;
        }

        if($isObject) return reset($chats);

        return $chats;
    }

    /**
     * Get chat list.
     *
     * @param  bool   $public
     * @access public
     * @return array
     */
    public function getList($public = true)
    {
        $chats = $this->dao->select('*')->from(TABLE_IM_CHAT)
            ->where('public')->eq($public)
            ->beginIF($public)->andWhere('dismissDate')->eq('0000-00-00 00:00:00')->fi()
            ->fetchAll();

        return $this->formatChats($chats);
    }

    /**
     * Get output data of chat list.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getListOutput($userID)
    {
        $output = new stdclass();
        $output->module = 'chat';
        $output->method = 'getList';

        $chatList = $this->getListByUserID($userID);
        foreach($chatList as $chat)
        {
            $chat->members = $this->getMemberListByGID($chat->gid);
        }
        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Get chat list failed.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = array($userID);
            $output->data   = $chatList;
        }
        return $output;
    }

    /**
     * Get chat list by userID.
     *
     * @param  int    $userID
     * @param  bool   $star
     * @access public
     * @return array
     */
    public function getListByUserID($userID = 0, $star = false)
    {
        $systemChat = $this->dao->select('*, 0 as star, 0 as hide, 0 as mute')
            ->from(TABLE_IM_CHAT)
            ->where('type')->eq('system')
            ->fetchAll();

        $chats = $this->dao->select('t1.*, t2.star, t2.hide, t2.mute, t2.category')
            ->from(TABLE_IM_CHAT)->alias('t1')
            ->leftjoin(TABLE_IM_CHATUSER)->alias('t2')->on('t1.gid=t2.cgid')
            ->where('t2.quit')->eq('0000-00-00 00:00:00')
            ->andWhere('t2.user')->eq($userID)
            ->beginIF($star)->andWhere('t2.star')->eq($star)->fi()
            ->fetchAll();

        $chats = array_merge($systemChat, $chats);

        return $this->formatChats($chats);
    }

    /**
     * Get a chat by gid.
     *
     * @param  string $gid
     * @param  bool   $members
     * @access public
     * @return object
     */
    public function getByGID($gid = '', $members = false)
    {
        $chat = $this->dao->select('*')->from(TABLE_IM_CHAT)->where('gid')->eq($gid)->fetch();

        if($chat)
        {
            $chat = $this->formatChats($chat);

            if($members) $chat->members = $this->getMemberListByGID($gid);
        }

        return $chat;
    }

    /**
     * Get offline messages.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getOfflineMessages($userID = 0)
    {
        $messages = $this->dao->select('t2.*')->from(TABLE_IM_MESSAGESTATUS)->alias('t1')
            ->leftJoin(TABLE_IM_MESSAGE)->alias('t2')->on('t2.id = t1.message')
            ->where('t1.user')->eq($userID)
            ->andWhere('t1.status')->eq('waiting')
            ->andWhere('t2.type')->ne('notify')
            ->orderBy('t2.order desc, t2.id desc')
            ->fetchAll();
        if(empty($messages)) return array();

        $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)
            ->where('user')->eq($userID)
            ->andWhere('status')->eq('waiting')
            ->exec();
        return $messages;
    }

    /**
     * Get output data of offline messages.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getOfflineMessagesOutput($userID)
    {
        $output = new stdclass();
        $output->module = 'chat';
        $output->method = 'message';

        $messages = $this->getOfflineMessages($userID);
        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Get offline messages fail.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = array($userID);
            $output->data   = $messages;
        }
        return $output;
    }

    /**
     * Get chat group pairs.
     *
     * @access public
     * @return array
     */
    public function getChatGroupPairs()
    {
        return $this->dao->select('gid, name')->from(TABLE_IM_CHAT)
            ->where('type')->eq('group')
            ->andWhere('dismissDate')->eq('0000-00-00 00:00:00')
            ->fetchPairs();
    }

    /**
     * Get all user pairs or users of one chat group.
     *
     * @param  string $gid
     * @access public
     * @return array
     */
    public function getChatUserPairs($gid = '')
    {
        $userList = $this->dao->select('id, user')->from(TABLE_IM_CHATUSER)
            ->where('quit')->eq('0000-00-00 00:00:00')
            ->beginIF($gid)->andWhere('cgid')->eq($gid)->fi()
            ->fetchPairs('id', 'user');
        $userPais = $this->dao->select('id, realname')->from(TABLE_USER)->where('id')->in($userList)->fetchPairs();

        if(empty($userPais)) return array();
        return $userPais;
    }

    /**
     * Create a chat.
     *
     * @param  string $gid
     * @param  string $name
     * @param  string $type
     * @param  array  $members
     * @param  int    $subjectID
     * @param  bool   $public
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function create($gid = '', $name = '', $type = '', $members = array(), $subjectID = 0, $public = false, $userID = 0)
    {
        $user = $this->getUserByUserID($userID);

        $chat = new stdclass();
        $chat->gid         = $gid;
        $chat->name        = $name;
        $chat->type        = $type;
        $chat->subject     = $subjectID;
        $chat->createdBy   = !empty($user->account) ? $user->account : '';
        $chat->createdDate = helper::now();

        if($public) $chat->public = 1;

        $this->dao->insert(TABLE_IM_CHAT)->data($chat)->exec();

        /* Add members to chat. */
        foreach($members as $member)
        {
            $this->joinChat($gid, $member);
        }

        return $this->getByGID($gid, true);
    }

    /**
     * Get content of broadcast.
     *
     * @param  string $type
     * @param  object $chat
     * @param  int    $userID
     * @param  array  $members
     * @access public
     * @return string
     */
    public function getBroadcastContent($type, $chat, $userID, $members)
    {
        $user = $this->getUserByUserID($userID);

        if($type == 'createChat' or $type == 'renameChat') return sprintf($this->lang->chat->broadcast->$type, $user->account, $chat->name, $chat->gid);

        if($type == 'inviteUser')
        {
            $memberIDs = array();
            foreach($members as $member) $memberIDs[] = '@#' . $member;
            $memberIDs = implode($this->lang->chat->connector, $memberIDs);

            return sprintf($this->lang->chat->broadcast->$type, $user->account, $memberIDs);
        }

        return sprintf($this->lang->chat->broadcast->$type, $user->account);
    }

    /**
     * Create a output of broadcast.
     *
     * @param  string $type
     * @param  object $chat
     * @param  array  $onlineUsers
     * @param  int    $userID
     * @param  array  $members
     * @access public
     * @return object
     */
    public function createBroadcast($type, $chat, $onlineUsers, $userID, $members = array())
    {
        $adminUsers = array();

        $message = new stdclass();
        $message->gid         = $this->createGID();
        $message->cgid        = $chat->gid;
        $message->type        = 'broadcast';
        $message->contentType = 'text';
        $message->content     = $this->getBroadcastContent($type, $chat, $userID, $members);
        $message->date        = helper::now();
        $message->user        = $userID;
        $message->order       = 1;

        /* If quit a chat, only send broadcast to the admins or the created user of chat. */
        if($type == 'quitChat')
        {
            if($chat->admins) $adminUsers = explode(',', trim($chat->admins, ','));
            if(!$adminUsers)
            {
                $user = $this->loadModel('user')->getByAccount($chat->createdBy);
                if($user) $adminUsers = array($user->id);
            }
            $users       = $this->getUserList($status = 'online', $adminUsers);
            $onlineUsers = array_keys($users);
        }

        /* Save broadcast to im_message. */
        $messages     = $this->createMessage(array($message), $userID);
        $offlineUsers = $this->getUserList($status = 'offline', $adminUsers);
        $this->saveOfflineMessages($messages, array_keys($offlineUsers));

        $output = new stdclass();
        $output->module = 'chat';
        $output->method = 'message';

        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Send message failed.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = $onlineUsers;
            $output->data   = $messages;
        }

        return $output;
    }

    /**
     * Update a chat.
     *
     * @param  object $chat
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function update($chat = null, $userID = 0)
    {
        if($chat)
        {
            $user = $this->getUserByUserID($userID);
            $chat->editedBy   = !empty($user->account) ? $user->account : '';
            $chat->editedDate = helper::now();
            $this->dao->update(TABLE_IM_CHAT)->data($chat)->where('gid')->eq($chat->gid)->batchCheck($this->config->chat->require->edit, 'notempty')->exec();
        }

        /* Return the changed chat. */
        return $this->getByGID($chat->gid, true);
    }

    /**
     * Set admins of a chat.
     *
     * @param  string $gid
     * @param  array  $admins
     * @param  bool   $isAdmin
     * @access public
     * @return object
     */
    public function setAdmin($gid = '', $admins = array(), $isAdmin = true)
    {
        $chat = $this->getByGID($gid);
        $adminList = explode(',', $chat->admins);
        foreach($admins as $admin)
        {
            if($isAdmin)
            {
                $adminList[] = $admin;
            }
            else
            {
                $key = array_search($admin, $adminList);
                if($key) unset($adminList[$key]);
            }
        }
        $adminList = implode(',', $adminList);
        $this->dao->update(TABLE_IM_CHAT)->set('admins')->eq($adminList)->where('gid')->eq($gid)->exec();

        return $this->getByGID($gid, true);
    }

    /**
     * Star or cancel star a chat.
     *
     * @param  string $gid
     * @param  bool   $star
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function starChat($gid = '', $star = true, $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('star')->eq($star)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return $this->getByGID($gid, true);
    }

    /**
     * Hide or display a chat.
     *
     * @param  string $gid
     * @param  bool   $hide
     * @param  int    $userID
     * @access public
     * @return bool
     */
    public function hideChat($gid = '', $hide = true, $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('hide')->eq($hide)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Mute a chat.
     *
     * @param  string $gid
     * @param  bool   $mute
     * @param  int    $userID
     * @access public
     * @return bool
     */
    public function muteChat($gid = '', $mute = true, $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('mute')->eq($mute)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Set category for a chat
     *
     * @param  array  $gids
     * @param  string $category
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function categoryChat($gids = array(), $category = '', $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('category')->eq($category)
            ->where('cgid')->in($gids)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Join or quit a chat.
     *
     * @param  string $gid
     * @param  int    $userID
     * @param  bool   $join
     * @access public
     * @return bool
     */
    public function joinChat($gid = '', $userID = 0, $join = true)
    {
        if($join)
        {
            /* Join chat. */
            $data = $this->dao->select('*')->from(TABLE_IM_CHATUSER)->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->fetch();
            if($data)
            {
                /* If user hasn't quit the chat then return. */
                if($data->quit == '0000-00-00 00:00:00') return true;

                /* If user has quited the chat then update the record. */
                $data = new stdclass();
                $data->join = helper::now();
                $data->quit = '0000-00-00 00:00:00';
                $this->dao->update(TABLE_IM_CHATUSER)->data($data)->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->exec();

                return !dao::isError();
            }

            /* Create a new record about user's chat info. */
            $data = new stdclass();
            $data->cgid = $gid;
            $data->user = $userID;
            $data->join = helper::now();

            $this->dao->insert(TABLE_IM_CHATUSER)->data($data)->exec();

            $id = $this->dao->lastInsertID();

            $this->dao->update(TABLE_IM_CHATUSER)->set('`order`')->eq($id)->where('id')->eq($id)->exec();
        }
        else
        {
            /* Quit chat. */
            $this->dao->update(TABLE_IM_CHATUSER)->set('quit')->eq(helper::now())->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->exec();
        }
        return !dao::isError();
    }

    /**
     * Create messages.
     *
     * @param  array  $messageList
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function createMessage($messageList = array(), $userID = 0)
    {
        $idList   = array();
        $chatList = array();
        foreach($messageList as $message)
        {
            $msg = $this->dao->select('*')->from(TABLE_IM_MESSAGE)->where('gid')->eq($message->gid)->fetch();
            if($msg)
            {
                if($msg->contentType == 'image' || $msg->contentType == 'file')
                {
                    $this->dao->update(TABLE_IM_MESSAGE)->set('content')->eq($message->content)->where('id')->eq($msg->id)->exec();
                }
                $idList[] = $msg->id;
            }
            elseif(!$msg)
            {
                if(!(isset($message->user) && $message->user)) $message->user = $userID;
                if(!(isset($message->date) && $message->date)) $message->date = helper::now();

                $this->dao->insert(TABLE_IM_MESSAGE)->data($message)->exec();
                $idList[] = $this->dao->lastInsertID();
            }
            $chatList[$message->cgid] = $message->cgid;
        }
        if(empty($idList)) return array();

        $this->dao->update(TABLE_IM_CHAT)->set('lastActiveTime')->eq(helper::now())->where('gid')->in($chatList)->exec();

        return $this->getMessageList($idList);
    }

    /**
     * Retract one message.
     *
     * @param  string $gid
     * @access public
     * @return void
     */
    public function retractMessage($gid = '')
    {
        $message = $this->dao->select('id, gid, cgid, user, date, `order`, deleted, type, contentType')->from(TABLE_IM_MESSAGE)->where('gid')->eq($gid)->fetch();

        $messageLife = (strtotime(helper::now()) - strtotime($message->date)) / 60;
        if($messageLife <= $this->config->chat->retract->validTime)
        {
            $message->deleted = 1;
            $this->dao->update(TABLE_IM_MESSAGE)->set('deleted')->eq($message->deleted)->where('gid')->eq($gid)->exec();
        }

        $message->date  = strtotime($message->date);
        $message->order = (int)($message->order);
        $message->id    = (int)($message->id);
        $message->user  = (int)($message->user);
        $messages = array();
        $messages[] = $message;

        return $messages;
    }

    /**
     * Save offline messages.
     *
     * @param  array  $messages
     * @param  array  $users
     * @access public
     * @return bool
     */
    public function saveOfflineMessages($messages = array(), $users = array())
    {
        foreach($messages as $message)
        {
            $this->batchCreateMessageStatus($users, $message->id, 'waiting');
        }
        return !dao::isError();
    }

    /**
     * Get offline notify.
     * @param $userID
     * @return array
     */
    public function getOfflineNotify($userID)
    {
        $messages = $this->dao->select('t2.*')->from(TABLE_IM_MESSAGESTATUS)->alias('t1')
                ->leftjoin(TABLE_IM_MESSAGE)->alias('t2')->on("t2.id = t1.message")
                ->where('t1.user')->eq($userID)
                ->andWhere('t1.status')->eq('waiting')
                ->andWhere('t2.type')->eq('notify')
                ->fetchAll();

        if(empty($messages)) return array();
        $notify   = $this->formatNotify($messages);
        $messages = array();
        foreach($notify as $message) $messages[] = $message->id;

        $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)->where('message')->in($messages)->andWhere('user')->eq($userID)->exec();
        return $notify;
    }

    /**
     * Get output data of offline notify.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getOfflineNotifyOutput($userID)
    {
        $output = new stdclass();
        $output->module = 'chat';
        $output->method = 'notify';

        $messages = $this->getOfflineNotify($userID);
        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Get offline notify fail.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = array($userID);
            $output->data   = $messages;
        }
        return $output;
    }

    /**
     * Get history for login.
     * @param $user
     * @param $device
     * @return object
     */
    public function getHistoryOutput($user, $device = 'desktop')
    {
        $output = new stdclass();
        $output->module = 'chat';
        $output->method = 'history';

        $gids      = $this->getGidByUserID($user->id);
        $messages  = array();
        $startDate = $this->loadModel('setting')->getItem("owner={$user->account}&module=common&section=lastLogin&key={$device}");
        
        if(!empty($startDate) && !empty($gids))
        {
            $messages = $this->dao->select('*')->from(TABLE_IM_MESSAGE)
                ->where('cgid')->in($gids)
                ->beginIF($startDate)->andWhere('date')->ge($startDate)->fi()
                ->orderBy('id_desc')->limit(500)
                ->fetchAll();
            foreach($messages as $message)
            {
                $message->id    = (int)$message->id;
                $message->order = (int)$message->order;
                $message->user  = (int)$message->user;
                $message->date  = strtotime($message->date);
            }
        }
        
        $this->loadModel('setting')->setItem($user->account . '.common.lastLogin.' . $device, date(DT_DATETIME1));
        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Get offline notify fail.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = array($user->id);
            $output->data   = $messages;
        }
        return $output;
    }

    /**
     * Get notify.
     * @access public
     * @return array
     */
    public function getNotify()
    {
        $onlineUsers = $this->getUserList('online');
        if(empty($onlineUsers)) return array();
        $onlineUsers = array_keys($onlineUsers);

        $messages = $this->dao->select('t2.*')->from(TABLE_IM_MESSAGESTATUS)->alias('t1')
                ->leftJoin(TABLE_IM_MESSAGE)->alias('t2')->on('t2.id = t1.message')
                ->where('t1.status')->eq('waiting')
                ->andWhere('t2.type')->eq('notify')
                ->andWhere('t1.user')->in($onlineUsers)
                ->groupBy('t1.message')
                ->fetchAll();
        if(empty($messages)) return array();

        $notify   = $this->formatNotify($messages);
        $data     = array();
        $messages = array();
        foreach($notify as $message)
        {
            foreach($onlineUsers as $userID)
            {
                if((empty($message->user) && empty($message->users)) || in_array($userID, $message->users))
                {
                    $messages[$userID][] = $message->id;
                    $data[$userID][]     = $message;
                }
            }
        }

        foreach($messages as $userID => $message)
        {
            $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)
                ->where('message')->in($message)
                ->andWhere('user')->eq($userID)
                ->exec();
        }
        return $data;
    }

    /**
     * Foramt messages for notify.
     * @param object $messages
     * @access public
     * @return array
     */
    public function formatNotify($messages)
    {
        $notify = array();
        foreach($messages as $message)
        {
            $data = new stdClass();
            $messageData = json_decode($message->data);
            $data->id          = $message->id;
            $data->gid         = $message->gid;
            $data->cgid        = $message->cgid;
            $data->type        = $message->type;
            $data->content     = $message->deleted ? '' : $message->content;
            $data->date        = strtotime($message->date);
            $data->contentType = $message->contentType;
            $data->title       = $messageData->title;
            $data->subtitle    = $messageData->subtitle;
            $data->url         = $messageData->url;
            $data->actions     = $messageData->actions;
            $data->sender      = $messageData->sender;
            $data->users       = $messageData->target;

            $notify[] = $data;
        }
        return $notify;
    }

    /**
     * Upgrade offline user status.
     * @param array $offline
     * @access public
     * @return bool
     */
    public function offlineUser($offline = array())
    {
        if(empty($offline)) return true;
        $this->dao->update(TABLE_USER)->set('clientStatus')->eq('offline')->where('id')->in($offline)->exec();
        return !dao::isError();
    }

    /**
     * Add offline messages according to the gid of messages that failed to be sent.
     * @param array $sendfail
     * @access public
     * @return bool
     */
    public function sendFailMessage($sendfail = array())
    {
        foreach($sendfail as $userID => $gid)
        {
            if(empty($gid)) continue;
            $idList   = $this->dao->select('id')->from(TABLE_IM_MESSAGE)->where('gid')->in($gid)->fetchPairs();
            $messages = $this->getMessageList($idList);
            $this->saveOfflineMessages($messages, $userID);
        }
        return !dao::isError();
    }

    /**
     * Insert message for notify.
     * @param array  $target
     * @param string $title
     * @param string $subtitle
     * @param string $content
     * @param string $contentType
     * @param string $url
     * @param array  $actions
     * @param int    $sender
     * @access public
     * @return bool
     */
    public function createNotify($target = '', $title = '', $subtitle = '', $content = '', $contentType = 'text', $url = '', $actions = array(), $sender = 0)
	{
	    if(is_array($target))
        {
            $cgid = 'notification';
        }
        else
        {
            $cgid   = $target;
            $target = $this->dao->select('user')->from(TABLE_IM_CHATUSER)->where('cgid')->eq($target)->fetchPairs('user');
        }
        $users = $this->getUserList('', $target);

		$info = array();
		$info['title']    = $title;
		$info['subtitle'] = $subtitle;
		$info['url']	  = $url;
		$info['actions']  = $actions;
		$info['sender']	  = $sender;
		$info['target']	  = array_keys($users);

		$notify = new stdClass();
		$notify->gid		 = $this->createGID();
		$notify->cgid		 = $cgid;
		$notify->user		 = 0;
		$notify->date		 = helper::now();
		$notify->order		 = 0;
		$notify->type		 = 'notify';
		$notify->content     = $content;
		$notify->contentType = $contentType;
		$notify->data		 = json_encode($info);

		$this->dao->insert(TABLE_IM_MESSAGE)->data($notify)->exec();
		$message = $this->dao->lastInsertID();
		$this->batchCreateMessageStatus($info['target'], $message, 'waiting');
        return !dao::isError();
    }

    public function batchCreateMessageStatus($users, $message, $status = 'waiting')
    {
        if(empty($users) || empty($message)) return false;
        foreach($users as $user)
        {
            $data = new stdClass();
            $data->user    = $user;
            $data->message = $message;
            $data->status  = $status;
            $this->dao->replace(TABLE_IM_MESSAGESTATUS)->data($data)->exec();
        }
        return !dao::isError();
    }

	/**
	 * Create gid.
	 * @access public
	 * @return string
	 */
	public function createGID()
	{
	    $id = md5(microtime() . mt_rand());
        return substr($id, 0, 8) . '-' . substr($id, 8, 4) . '-' . substr($id, 12, 4) . '-' . substr($id, 16, 4) . '-' . substr($id, 20, 12);
	}

    /**
     * Check for user data changes.
     *
     * @return string
     */
    public function checkUserChange()
    {
        $data = $this->dao->select('id')->from(TABLE_ACTION)
            ->where('objectType')->eq('user')
            ->andWhere('action')->in('created,edited,deleted')
            ->andWhere('date')->gt(date(DT_DATETIME1, strtotime('-1 Minute')))
            ->fetch();
        return empty($data) ? 'no' : 'yes';
    }

    /**
     * Get extension list.
     * @param $userID
     * @return array
     */
    public function getExtensionList($userID)
    {
        $entries    = array();
        $allEntries = array();
        $time       = time();
        $baseURL    = commonModel::getSysURL();
        $entryList  = $this->dao->select('*')->from(TABLE_ENTRY)->orderBy('`order`, id')->fetchAll();
        $files      = $this->dao->select('id, pathname, objectID')->from(TABLE_FILE)->where('objectType')->eq('entry')->fetchAll('objectID');

        foreach($entryList as $entry)
        {
            $data = new stdclass();
            $data->id     = $entry->id;
            $data->url    = strpos($entry->login, 'http') !== 0 ? str_replace('../', $baseURL . $this->config->webRoot, $entry->login) : $entry->login;
            $allEntries[] = $data;
        }

        $_SERVER['SCRIPT_NAME'] = str_replace('x.php', 'index.php', $_SERVER['SCRIPT_NAME']);
        foreach($entryList as $entry)
        {
            if($entry->status != 'online') continue;
            if(strpos(',' . $entry->platform . ',', ',xuanxuan,') === false) continue;

            $token = '';
            if(isset($files[$entry->id]->pathname))
            {
                $token = '&time=' . $time . '&token=' . md5($files[$entry->id]->pathname . $time);
            }
            $data = new stdClass();
            $data->entryID     = (int)$entry->id;
            $data->name        = $entry->code;
            $data->displayName = $entry->name;
            $data->abbrName    = $entry->abbr;
            $data->webViewUrl  = strpos($entry->login, 'http') !== 0 ? str_replace('../', $baseURL . $this->config->webRoot, $entry->login) : $entry->login;
            $data->download    = empty($entry->package) ? '' : $baseURL . helper::createLink('file', 'download', "fileID={$entry->package}&mouse=" . $token);
            $data->md5         = empty($entry->package) ? '' : md5($entry->package);
            $data->logo        = empty($entry->logo)    ? '' : $baseURL . $this->config->webRoot . ltrim($entry->logo, '/');

            if($entry->sso) $data->data = $allEntries;

            $entries[] = $data;
        }

        return $entries;
    }

    /**
     * Download xxd.
     *
     * @param  int    $setting
     * @param  string $type
     * @access public
     * @return void
     */
    public function downloadXXD($setting, $downloadType)
    {
        set_time_limit(0);
        $system          = $this->getSystem($setting->os);
        $version         = $this->config->xuanxuan->version;
        $xxdDirectory    = $this->app->tmpRoot . 'xxd' . DS . $version;
        $basePackage     = $xxdDirectory . DS . $system .  ".base.zip";
        $xxdFileName     = 'xxd.' . $version . ".$system" .  ".zip";
        $downloadCDNLink = $this->config->chat->xxdDownloadUrl . $version . "/xxd." . $version . ".$system" .  ".zip";

        if(!is_dir($xxdDirectory)) mkdir($xxdDirectory, 0777, true);
        if(!file_exists($basePackage) && $downloadType == 'package')
        {
            $agent = $this->app->loadClass('snoopy');
            $agent->fetch($downloadCDNLink);
            if(!empty(json_decode($agent->results)->error)) return array('result' => 'fail', 'message' => "$basePackage is not exists");
            $fopenPackage = fopen($basePackage, "w");
            fwrite($fopenPackage, $agent->results);
        }

        $data                 = new stdClass();
        $data->xxdDirectory   = $xxdDirectory;
        $data->sslcrt         = $setting->sslcrt;
        $data->sslkey         = $setting->sslkey;
        $data->basePackage    = $basePackage;
        $data->xxdFileName    = $xxdFileName;
        $data->host           = trim($this->getServer(), '/') . (zget($this->config->xuanxuan, 'backend', 'xxb') == 'ranzhi' ? dirname($this->config->webRoot) : $this->config->webRoot);

        $data->ip             = $setting->ip;
        $data->commonPort     = $setting->commonPort;
        $data->chatPort       = $setting->chatPort;
        $data->https          = $setting->https;
        $data->uploadPath     = 'files/';
        $data->uploadFileSize = $setting->uploadFileSize;
        $data->pollingInterval= isset($this->config->xuanxuan->pollingInterval) ? $this->config->xuanxuan->pollingInterval : 60;
        $data->maxOnlineUser  = isset($setting->maxOnlineUser) ? $setting->maxOnlineUser : 0;
        $data->logPath        = 'log/';
        $data->certPath       = 'cert/';
        $data->debug          = 0;
        $data->key            = $this->config->xuanxuan->key;

        if($downloadType == 'config')
        {
            $configContent = $this->createXxdConfigFile($data);
            if(!empty($configContent)) $this->loadModel('file')->sendDownHeader('xxd.conf', 'conf', $configContent['zh']);
        }
        elseif($downloadType == 'package')
        {
            $packageFileName = $this->createXxdPackage($data);
            if(!empty($packageFileName)) return array('result' => 'success', 'message' => helper::createLink('chat', 'downloadXxdPackage', "xxdFileName=$xxdFileName"));
        }

        return array('result' => 'fail', 'message' => 'error');
    }

    /**
     * create xxd config file
     *
     * @param object $setting
     * @access public
     * @return string
     */
    public function createXxdConfigFile($setting)
    {
        $configParamsList = $this->config->chat->xxdConfig;

        // Replace template variable.
        $lineMaxLength = 0;
        foreach($configParamsList as  $configParams)
        {
            if($configParams == 'host' || $configParams == 'key')
            {
                $config[$configParams] = $setting->$configParams;
            }
            else
            {
                $config[$configParams] = $configParams . '=' . $setting->$configParams;
                if($configParams == 'uploadFileSize') $config[$configParams] .= 'M';
            }
            $lineMaxLength = strlen($configParams) > $lineMaxLength ? strlen($configParams) : $lineMaxLength;
        }
        $lineMaxLength += 10;

        // Add parameter notes
        $contentZH = '[server]' . "\n";
        $contentEN = '[server]' . "\n";
        foreach($config as $type => $configValue)
        {
            if($type == 'host' || $type == 'key') continue;
            $configValue  = str_replace(PHP_EOL, '', $configValue);
            $configlength = strlen($configValue);

            for($i = 0; $i < ($lineMaxLength - $configlength); $i++) $configValue .= ' ';
            $contentZH .= $configValue . $this->lang->chat->xxdConfigNote['zh'][$type] . "\n";
            $contentEN .= $configValue . $this->lang->chat->xxdConfigNote['en'][$type] . "\n";
        }

        // Add backend
        $backend     = "\n" . '[backend]' . "\n";
        $backendFoot = 'default=' . $config['host'] . 'x.php,' . $config['key'];
        $backendFoot = str_replace(PHP_EOL, '', $backendFoot) . "\n";
        $backendZH   = $backend . $this->lang->chat->xxdConfigNote['zh']['backend'] . "\n" . $backendFoot;
        $backendEN   = $backend . $this->lang->chat->xxdConfigNote['en']['backend'] . "\n" . $backendFoot;
        $contentZH  .= $backendZH;
        $contentEN  .= $backendEN;

        return array('zh' => $contentZH, 'en' => $contentEN);
    }

    /**
     * create xxd package
     *
     * @param object
     * @access public
     * @return string
     */
    public function createXxdPackage($setting)
    {
        $configContent = $this->createXxdConfigFile($setting);
        if(empty($configContent)) return false;

        // unzip package 
        $this->app->loadClass('pclzip', true);
        $basePackage = new pclzip($setting->basePackage);
        $result  = $basePackage->extract(
            PCLZIP_OPT_PATH, $setting->xxdDirectory
        );
        if($result == 0) $basePackage->errorInfo(true);

        // Replace config file.
        $baseFilePath = $result[0]['filename'];
        $packageName  = $result[0]['stored_filename'];
        unlink($baseFilePath . 'config/xxd.conf');
        unlink($baseFilePath . 'config/xxd.en.conf');
        file_put_contents($baseFilePath . 'config/xxd.conf', $configContent['zh']);
        file_put_contents($baseFilePath . 'config/xxd.en.conf', $configContent['en']);

        // https add certificate
        if(isset($setting->https) && $setting->https == 'on')
        {
            if(!is_dir($baseFilePath . 'cert')) mkdir($baseFilePath . 'cert', 0777);
            file_put_contents($baseFilePath . 'cert/xxd.crt', $setting->sslcrt);
            file_put_contents($baseFilePath . 'cert/xxd.key', $setting->sslkey);
        }

        // zip xxd file 
        chdir($setting->xxdDirectory);
        $xxdZipName = $setting->xxdDirectory . "/" . $setting->xxdFileName;
        $xxdZip     = new pclzip($xxdZipName);
        $xxdResult  = $xxdZip->create($packageName, PCLZIP_OPT_TEMP_FILE_ON);
        if($xxdResult == 0) return false;
        return $xxdResult[0]['filename'];
    }

    /**
     * revise operating system name.
     *
     * @param string $os name
     * @access public
     * @return string
     */
    public function getSystem($os)
    {
        switch($os)
        {
            case 'win_i386':
               return 'win32';
            case 'win_x86_64':
               return 'win64';
            case 'linux_i386':
               return 'linux.ia32';
            case 'linux_x86_64':
               return 'linux.x64';
            case 'darwin_x86_64':
               return 'mac';
        }
    }

    /**
     * Get server.
     *
     * @access public
     * @return string
     */
    public function getServer()
    {
        if(!empty($this->config->xuanxuan->server)) return $this->config->xuanxuan->server;

        return commonModel::getSysURL();
    }

    /**
     * UploadFile a file.
     *
     * @param  string $fileName
     * @param  string $path
     * @param  int    $size
     * @param  int    $time
     * @param  int    $userID
     * @param  string $users
     * @param  object $chat
     * @access public
     * @return int
     */
    public function uploadFile($fileName, $path, $size, $time, $userID, $users, $chat)
    {
        $user      = $this->getUserByUserID($userID);
        $extension = $this->loadModel('file')->getExtension($fileName);

        $file = new stdclass();
        $file->pathname    = $path;
        $file->title       = rtrim($fileName, ".$extension");
        $file->extension   = $extension;
        $file->size        = $size;
        $file->objectType  = 'chat';
        $file->objectID    = $chat->id;
        $file->createdBy   = !empty($user->account) ? $user->account : '';
        $file->createdDate = date(DT_DATETIME1, $time);

        $this->dao->insert(TABLE_FILE)->data($file)->exec();

        $fileID = $this->dao->lastInsertID();
        $path  .= md5($fileName . $fileID . $time);
        $this->dao->update(TABLE_FILE)->set('pathname')->eq($path)->where('id')->eq($fileID)->exec();

        return $fileID;
    }

    /**
     * Save xxd start time.
     * 
     * @access public
     * @return void
     */
    public function setXxdStartTime()
    {
        $this->loadModel('setting')->setItem('system.common.xxd.start', helper::now());

        return !dao::isError();
    }

    /**
     * update last poll.
     * 
     * @access public
     * @return void
     */
    public function updateLastPoll()
    {
        $this->loadModel('setting')->setItem('system.common.chat.lastPoll', helper::now());
    }

    /**
     * check xxb config.
     * 
     * @access public
     * @return bool
     */
    public function checkXXBConfig()
    {
        $result        = true;
        $xxbConfig     = $this->config->xuanxuan;
        $notEmptyFields = array('key', 'server', 'ip', 'chatPort', 'commonPort');

        foreach($notEmptyFields as $field) if(empty($xxbConfig->$field)) $result = false;
        if($xxbConfig->https == 'on')
        {
            if(empty($xxbConfig->sslcrt)) $result = false;
            if(empty($xxbConfig->sslkey)) $result = false;
        }
        return $result;
    }

    /**
     * Get xxd run time. 
     * 
     * @access public
     * @return Y/H
     */
    public function getXxdRunTime($timestamp, $count = 0)
    {
        if($count > 1) return;

        if($timestamp > 86400)
        {
            return floor($timestamp / 86400) . $this->lang->chat->day . $this->getXxdRunTime($timestamp%86400, ++$count);
        }
        else if($timestamp > 3600)
        {
            return floor($timestamp / 3600) . $this->lang->chat->hours . $this->getXxdRunTime($timestamp%3600, ++$count);
        }
        else if($timestamp > 60)
        {
            return floor($timestamp / 60) . $this->lang->chat->minute . $this->getXxdRunTime($timestamp%60, ++$count);
        }
        else
        {
            return $timestamp . $this->lang->chat->secs;
        }
    }

    /**
     * Get xxd status. 
     * 
     * @access public
     * @return Y/H
     */
    public function getXxdStatus()
    {
        $this->app->loadLang('client');
        $now          = helper::now();
        $xxdStatus    = 'offline';
        $polling      = empty($this->config->xuanxuan->pollingInterval) ? 60 : $this->config->xuanxuan->pollingInterval;
        $lastPoll     = $this->loadModel('setting')->getItem("owner=system&module=common&section=chat&key=lastPoll");
        $xxdStartDate = zget($this->config->xxd, 'start', $this->lang->client->noData);

        if((strtotime($now) - strtotime($xxdStartDate) < $polling) || (strtotime($now) - strtotime($lastPoll)) < (3 + $polling))
        {
            $xxdStatus = 'online';
        }
        else if((strtotime($now) - strtotime($lastPoll)) > (3 + $polling))
        {
            $xxdStatus = 'offline';
        }

        return $xxdStatus;
    }

}
