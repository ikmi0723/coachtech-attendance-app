<?php

return [
    'required' => ':attributeを入力してください',
    'email' => ':attributeはメール形式で入力してください',
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'confirmed' => ':attributeと一致しません',

    'attributes' => [
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],

    'custom' => [
        'name' => [
            'required' => 'お名前を入力してください',
            'max' => 'お名前は255文字以内で入力してください',
        ],
        'email' => [
            'required' => 'メールアドレスを入力してください',
            'email' => 'メールアドレスはメール形式で入力してください',
        ],
        'password' => [
            'required' => 'パスワードを入力してください',
            'min' => 'パスワードは8文字以上で入力してください',
            'confirmed' => 'パスワードと一致しません',
        ],
        'password_confirmation' => [
            'required' => '確認用パスワードを入力してください',
        ],
    ],
];
