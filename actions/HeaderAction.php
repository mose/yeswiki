<?php

use YesWiki\Core\YesWikiAction;
use YesWiki\Core\Service\ThemeManager;

class HeaderAction extends YesWikiAction
{
    public function run()
    {
        $themeManager = $this->getService(ThemeManager::class);
        if (!$themeManager->loadTheme()) {
            if ($this->wiki->UserIsAdmin()) {
                $output = '<div style="border: red solid 4px;background: #FAA;padding: 2px;">'."\n";
                $output .= $themeManager->getErrorMessage();
                $text = _t('THEME_MANAGER_CLICK_TO_INSTALL'). THEME_PAR_DEFAUT ._t('THEME_MANAGER_AND_REPAIR');
                $output .= '<b><a href="'.$this->wiki->Href(
                    '',
                    'GererMisesAJour',
                    ['upgrade'=>  THEME_PAR_DEFAUT ]
                )
                    .'" title="'.$text.'">'.$text.'</a></b></div>';
                return $output ; // because an admin can see all website without theme
            } else {
                $output = $this->render('@templates\alert-message.twig', [
                    'type' => 'danger',
                    'message' => _t('TEMPLATE_NO_DEFAULT_THEME') .'<br><b>' .  _t('THEME_MANAGER_LOGIN_AS_ADMIN') . '</b>',
                ]);
                $output .= $this->callAction('login');
                exit($output);
            }
        } else {
            return $themeManager->renderHeader() ;
        }
    }
}