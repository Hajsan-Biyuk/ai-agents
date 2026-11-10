<?php

namespace Sheer\Webp\Controller;

use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use Sheer\Webp\AjaxService;

class Ajax extends Controller
{
    public function configureActions()
    {
        $cfg = [
            'prefilters' => [
                new ActionFilter\Authentication(),
                new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                new ActionFilter\Csrf(),
            ],
        ];

        return [
            'start' => $cfg,
            'stop' => $cfg,
            'chunk' => $cfg,
            'rollbackChunk' => $cfg,
            'installHtaccess' => $cfg,
            'clearQueue' => $cfg,
            'status' => $cfg,
        ];
    }

    public function startAction()
    {
        return $this->execute('start');
    }

    public function stopAction()
    {
        return $this->execute('stop');
    }

    public function chunkAction()
    {
        return $this->execute('chunk');
    }

    public function rollbackChunkAction()
    {
        return $this->execute('rollback_chunk');
    }

    public function installHtaccessAction()
    {
        return $this->execute('install_htaccess');
    }

    public function clearQueueAction()
    {
        return $this->execute('clear_queue');
    }

    public function statusAction()
    {
        return $this->execute('status');
    }

    private function execute($action)
    {
        if (!AjaxService::canWrite()) {
            $this->addError(new Error('denied'));
            return null;
        }

        return AjaxService::run($action);
    }
}
