<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Description: Advanced Community Engine
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 * Based on
 *   LiveStreet Engine Social Networking by Mzhelskiy Maxim
 *   Site: www.livestreet.ru
 *   E-mail: rus.engine@gmail.com
 *----------------------------------------------------------------------------
 */

/**
 * Сущность блога
 *
 * @package modules.blog
 * @since   1.0
 */
class ModuleBlog_EntityBlog extends Entity {
    /**
     * Возвращает ID блога
     *
     * @return int|null
     */
    public function getId() {

        return $this->getProp('blog_id');
    }

    /**
     * Возвращает ID владельца блога
     *
     * @return int|null
     */
    public function getOwnerId() {

        return $this->getProp('user_owner_id');
    }

    /**
     * Возвращает название блога
     *
     * @return string|null
     */
    public function getTitle() {

        return $this->getProp('blog_title');
    }

    /**
     * Возвращает описание блога
     *
     * @return string|null
     */
    public function getDescription() {

        return $this->getProp('blog_description');
    }

    /**
     * Возвращает тип блога
     *
     * @return string|null
     */
    public function getType() {

        return $this->getProp('blog_type');
    }

    /**
     * Возвращает сущность типа блога
     *
     * @return ModuleBlog_EntityBlogType|null
     */
    public function getBlogType() {

        $oBlogType = $this->getProp('blog_type_obj');
        if (!$oBlogType && ($sType = $this->getType())) {
            $oBlogType = $this->Blog_GetBlogTypeByCode($sType);
        }
        return $oBlogType;
    }

    /**
     * Возвращает дату создания блога
     *
     * @return string|null
     */
    public function getDateAdd() {

        return $this->getProp('blog_date_add');
    }

    /**
     * Возвращает дату редактирования блога
     *
     * @return string|null
     */
    public function getDateEdit() {

        return $this->getProp('blog_date_edit');
    }

    /**
     * Возвращает рейтинг блога
     *
     * @return string
     */
    public function getRating() {

        return number_format(round($this->getProp('blog_rating'), 2), 2, '.', '');
    }

    /**
     * Возврщает количество проголосовавших за блог
     *
     * @return int|null
     */
    public function getCountVote() {

        return $this->getProp('blog_count_vote');
    }

    /**
     * Возвращает количество пользователей в блоге
     *
     * @return int|null
     */
    public function getCountUser() {

        return $this->getProp('blog_count_user');
    }

    /**
     * Возвращает количество топиков в блоге
     *
     * @return int|null
     */
    public function getCountTopic() {

        return $this->getProp('blog_count_topic');
    }

    /**
     * Возвращает ограничение по рейтингу для постинга в блог
     *
     * @return int|null
     */
    public function getLimitRatingTopic() {

        return $this->getProp('blog_limit_rating_topic');
    }

    /**
     * Возвращает URL блога
     *
     * @return string|null
     */
    public function getUrl() {

        $sUrl = $this->getProp('blog_url');
        if (!$sUrl && $this->getType() == 'personal') {
            $sUrl = F::TranslitUrl($this->getOwner()->getLogin());
            if (!$sUrl) {
                $sUrl = 'user-' . $this->getOwnerId();
            }
        }
        return $sUrl;
    }

    /**
     * Возвращает полный серверный путь до аватара блога
     *
     * @return string|null
     */
    public function getAvatar() {

        return $this->getProp('blog_avatar');
    }

    /**
     * Возвращает расширения аватра блога
     *
     * @return string|null
     */
    public function getAvatarType() {

        return ($sPath = $this->getAvatarPath()) ? pathinfo($sPath, PATHINFO_EXTENSION) : null;
    }


    /**
     * Возвращает объект пользователя владельца блога
     *
     * @return ModuleUser_EntityUser|null
     */
    public function getOwner() {

        return $this->getProp('owner');
    }

    /**
     * Возвращает объект голосования за блог
     *
     * @return ModuleVote_EntityVote|null
     */
    public function getVote() {

        return $this->getProp('vote');
    }

    /**
     * Возвращает полный серверный путь до аватара блога определенного размера
     *
     * @param int $xSize    Размер аватара
     *
     * @return string
     */
    public function getAvatarUrl($xSize = 48) {

        if ($sUrl = $this->getAvatar()) {
            if (!$xSize) {
                return $sUrl;
            } else {
                $sModSuffix = F::File_ImgModSuffix($xSize, pathinfo($sUrl, PATHINFO_EXTENSION));
                $sUrl = $sUrl . $sModSuffix;
                if (Config::Get('module.image.autoresize')) {
                    $sFile = $this->Uploader_Url2Dir($sUrl);
                    if (!F::File_Exists($sFile)) {
                        $this->Img_Duplicate($sFile);
                    }
                }
                return $sUrl;
            }
        } else {
            $sPath = $this->Uploader_GetUserAvatarDir(0) . 'avatar_blog_' . Config::Get('view.skin', Config::LEVEL_CUSTOM) . '.png';
            if ($xSize) {
                if (is_string($xSize) && $xSize[0] == 'x') {
                    $xSize = substr($xSize, 1);
                }
                if ($nSize = intval($xSize)) {
                    $sPath .= '-' . $nSize . 'x' . $nSize . '.' . strtolower(pathinfo($sPath, PATHINFO_EXTENSION));
                }
            }
            if (Config::Get('module.image.autoresize') && !F::File_Exists($sPath)) {
                $this->Img_AutoresizeSkinImage($sPath, 'avatar_blog', $xSize ? $xSize : null);
            }
            return $this->Uploader_Dir2Url($sPath);
        }
    }

    public function getAvatarPath($nSize = 48) {

        return $this->getAvatarUrl($nSize);
    }

    /**
     * Возвращает факт присоединения пользователя к блогу
     *
     * @return bool|null
     */
    public function getUserIsJoin() {

        return $this->getProp('user_is_join');
    }

    /**
     * Проверяет является ли текущий пользователь администратором блога
     *
     * @return bool|null
     */
    public function getUserIsAdministrator() {

        return $this->getProp('user_is_administrator');
    }

    /**
     * Проверяет является ли текущий пользователь модератором блога
     *
     * @return bool|null
     */
    public function getUserIsModerator() {

        return $this->getProp('user_is_moderator');
    }

    /**
     * Returns link to the blog
     *
     * @return string
     */
    public function getLink() {

        if ($this->getType() == 'personal') {
            return $this->getOwner()->getUserUrl() . 'created/topics/';
        } else {
            return Router::GetPath('blog') . $this->getUrl() . '/';
        }
    }

    /**
     * Возвращает полный URL блога
     *
     * @return string
     */
    public function getUrlFull() {

        return $this->getLink();
    }

    /**
     * Устанавливает ID блога
     *
     * @param int $data
     */
    public function setId($data) {

        $this->setProp('blog_id', $data);
    }

    /**
     * Устанавливает ID владельца блога
     *
     * @param int $data
     */
    public function setOwnerId($data) {

        $this->setProp('user_owner_id', $data);
    }

    /**
     * Устанавливает заголовок блога
     *
     * @param string $data
     */
    public function setTitle($data) {

        $this->setProp('blog_title', $data);
    }

    /**
     * Устанавливает описание блога
     *
     * @param string $data
     */
    public function setDescription($data) {

        $this->setProp('blog_description', $data);
    }

    /**
     * Устанавливает тип блога
     *
     * @param string $data
     */
    public function setType($data) {

        $this->setProp('blog_type', $data);
    }

    /**
     * Устанавливает сущность типа блога
     *
     * @param ModuleBlog_EntityBlogType $data
     */
    public function setBlogType($data) {

        $this->setProp('blog_type_obj', $data);
        if (is_object($data) && $data instanceof ModuleBlog_EntityBlogType && $data->getTypeCode()) {
            $this->setType($data->getTypeCode());
        }
    }

    /**
     * Устанавливает дату создания блога
     *
     * @param string $data
     */
    public function setDateAdd($data) {

        $this->setProp('blog_date_add', $data);
    }

    /**
     * Устанавливает дату редактирования топика
     *
     * @param string $data
     */
    public function setDateEdit($data) {

        $this->setProp('blog_date_edit', $data);
    }

    /**
     * Устанавливает рейтинг блога
     *
     * @param float $data
     */
    public function setRating($data) {

        $this->setProp('blog_rating', $data);
    }

    /**
     * Устаналивает количество проголосовавших
     *
     * @param int $data
     */
    public function setCountVote($data) {

        $this->setProp('blog_count_vote', $data);
    }

    /**
     * Устанавливает количество пользователей блога
     *
     * @param int $data
     */
    public function setCountUser($data) {

        $this->setProp('blog_count_user', $data);
    }

    /**
     * Устанавливает количество топиков в блоге
     *
     * @param int $data
     */
    public function setCountTopic($data) {

        $this->setProp('blog_count_topic', $data);
    }

    /**
     * Устанавливает ограничение на постинг в блог
     *
     * @param float $data
     */
    public function setLimitRatingTopic($data) {

        $this->setProp('blog_limit_rating_topic', $data);
    }

    /**
     * Устанавливает URL блога
     *
     * @param string $data
     */
    public function setUrl($data) {

        $this->setProp('blog_url', $data);
    }

    /**
     * Устанавливает полный серверный путь до аватара блога
     *
     * @param string $data
     */
    public function setAvatar($data) {

        $this->setProp('blog_avatar', $data);
    }

    /**
     * Устанавливает владельца блога
     *
     * @param ModuleUser_EntityUser $data
     */
    public function setOwner($data) {

        $this->setProp('owner', $data);
    }

    /**
     * Устанавливает статус администратора блога для текущего пользователя
     *
     * @param bool $data
     */
    public function setUserIsAdministrator($data) {

        $this->setProp('user_is_administrator', $data);
    }

    /**
     * Устанавливает статус модератора блога для текущего пользователя
     *
     * @param bool $data
     */
    public function setUserIsModerator($data) {

        $this->setProp('user_is_moderator', $data);
    }

    /**
     * Устаналивает статус присоединения пользователя к блогу
     *
     * @param bool $data
     */
    public function setUserIsJoin($data) {

        $this->setProp('user_is_join', $data);
    }

    /**
     * Устанавливает объект голосования за блог
     *
     * @param ModuleVote_EntityVote $data
     */
    public function setVote($data) {

        $this->setProp('vote', $data);
    }

    /* *** Properties of blog type *** */
    public function IsPrivate() {

        $oBlogType = $this->getBlogType();
        if ($oBlogType) {
            return $oBlogType->IsPrivate();
        }
        return null;
    }

    public function IsReadOnly() {

        $oBlogType = $this->getBlogType();
        if ($oBlogType) {
            return $oBlogType->IsReadOnly();
        }
        return null;
    }

    public function IsHidden() {

        $oBlogType = $this->getBlogType();
        if ($oBlogType) {
            return $oBlogType->IsHidden();
        }
        return null;
    }

    /**
     * Checks if allows requires content type in this blog
     *
     * @param $xContentType
     *
     * @return bool
     */
    public function IsContentTypeAllow($xContentType) {

        if (!$xContentType) {
            return true;
        }

        if ($this->getBlogType()) {
            return $this->getBlogType()->IsContentTypeAllow($xContentType);
        }
        return false;
    }
}

// EOF