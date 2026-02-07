<?php

/**
 * Валидирует данные анкеты
 * @param array $data POST-данные
 * @return array список ошибок (пустой массив — всё ок)
 */
function validate_form(array $data): array
{
    $errors = [];

    
    if (
        empty($data['fio']) ||
        !preg_match('/^[А-Яа-яЁё\s]{1,150}$/u', $data['fio'])
    ) {
        $errors['fio'] =
            'ФИО должно содержать только буквы и пробелы (до 150 символов)';
    }

    
    if (
        empty($data['phone']) ||
        !preg_match('/^\+?[0-9\-\s()]{5,20}$/', $data['phone'])
    ) {
        $errors['phone'] =
            'Телефон может содержать только цифры, +, -, пробелы и скобки';
    }

    
    if (
        empty($data['email']) ||
        !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $errors['email'] = 'Некорректный email';
    }

    
    if (empty($data['birthdate'])) {
        $errors['birthdate'] = 'Дата рождения обязательна';
    }

    
    if (!in_array($data['gender'] ?? '', ['male', 'female'], true)) {
        $errors['gender'] = 'Недопустимое значение пола';
    }

    
    if (
        empty($data['languages']) ||
        !is_array($data['languages'])
    ) {
        $errors['languages'] =
            'Необходимо выбрать хотя бы один язык программирования';
    }

    
    if (empty($data['bio'])) {
        $errors['bio'] = 'Биография обязательна';
    }

    
    if (!isset($data['contract'])) {
        $errors['contract'] = 'Необходимо подтвердить ознакомление с контрактом';
    }


    return $errors;
}
