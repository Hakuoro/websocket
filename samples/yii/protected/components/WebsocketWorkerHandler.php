<?php

//пример реализации чата
class WebsocketWorkerHandler extends WebsocketWorker
{
    protected function onOpen($connectionId) {//вызывается при соединении с новым клиентом

    }

    protected function onClose($connectionId) {//вызывается при закрытии соединения клиентом

    }

    protected function onMessage($connectionId, $data, $type) {//вызывается при получении сообщения от клиента
        if (!strlen($data)) {
            return;
        }

        //var_export($data);
        //шлем всем сообщение, о том, что пишет один из клиентов
        //echo $data . "\n";
        $message = 'пользователь #' . $connectionId . ' (' . $this->pid . '): ' . $data;
        $this->sendToMaster($message);//отправляем сообщение на мастер, чтобы он разослал его на все воркеры

        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $data);
        }
    }

    protected function onMasterMessage($data) {//вызывается при получении сообщения от мастера
        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $data);
        }
    }
}