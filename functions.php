<?php

function debug(mixed $var, bool $flag = TRUE): void
{
    if ($flag) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    } else {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}


function encodePpassword(string $password): ?string
{
    if (is_string($password)) {
        return sha1(trim($password));
    } else {
        return NULL;
    }
}


function data_write(array $data): bool
{
    $file = __DIR__ . '/users.data';
    $data = json_encode($data) . PHP_EOL;
    return file_put_contents($file, $data, FILE_APPEND);
}


function data_read(): ?array
{
    $file = __DIR__ . '/users.data';
    $data = [];
    if (is_readable($file) && filesize($file)) {
        $handle = fopen($file, 'r');
        if ($handle) {
            while (!feof($handle)) {
                $line = fgets($handle);
                if ($line) {
                    $data[] = json_decode($line, TRUE);
                }
            }
            fclose($handle);
        }
        return $data;
    } else {
        return NULL;
    }
}

function getUserList(): ?array
{
    $users = data_read();
    if ($users) {
        $list = array_column(data_read(), 'password', 'login');
        return $list;
    } else {
        return NULL;
    }
}


function existsUser(string $login): bool
{
    $users = getUserList();
    if ($users) {
        $find = (array_key_exists($login, $users));
        return $find;
    } else {
        return FALSE;
    }
}

function checkPassword(string $login, string $password): bool
{
    if (existsUser($login)) {
        $user = getUserList();
        if ($user[$login] === encodePpassword($password)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}


function getCurrentUser(): ?string
{
    $name = $_SESSION['name'] ?? NULL;
    return $name;
}