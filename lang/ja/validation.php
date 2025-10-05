<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attributeを承認してください。',
    'accepted_if' => ':otherが:valueの場合、:attributeを承認してください。',
    'active_url' => ':attributeは有効なURLではありません。',
    'after' => ':attributeは:dateより後の日付にしてください。',
    'after_or_equal' => ':attributeは:date以降の日付にしてください。',
    'alpha' => ':attributeは英字のみにしてください。',
    'alpha_dash' => ':attributeは英数字・ハイフン・アンダースコアのみにしてください。',
    'alpha_num' => ':attributeは英数字のみにしてください。',
    'array' => ':attributeは配列にしてください。',
    'ascii' => ':attributeはASCII文字のみにしてください。',
    'before' => ':attributeは:dateより前の日付にしてください。',
    'before_or_equal' => ':attributeは:date以前の日付にしてください。',
    'between' => [
        'array' => ':attributeは:min個から:max個の要素にしてください。',
        'file' => ':attributeは:minKBから:maxKBのファイルにしてください。',
        'numeric' => ':attributeは:minから:maxの間の数値にしてください。',
        'string' => ':attributeは:min文字から:max文字にしてください。',
    ],
    'boolean' => ':attributeはtrueまたはfalseにしてください。',
    'can' => ':attributeには権限がありません。',
    'confirmed' => ':attributeの確認が一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attributeは有効な日付にしてください。',
    'date_equals' => ':attributeは:dateと同じ日付にしてください。',
    'date_format' => ':attributeは:formatの形式にしてください。',
    'decimal' => ':attributeは小数点以下:decimal桁にしてください。',
    'declined' => ':attributeを拒否してください。',
    'declined_if' => ':otherが:valueの場合、:attributeを拒否してください。',
    'different' => ':attributeは:otherと異なる値にしてください。',
    'digits' => ':attributeは:digits桁にしてください。',
    'digits_between' => ':attributeは:min桁から:max桁にしてください。',
    'dimensions' => ':attributeの画像サイズが正しくありません。',
    'distinct' => ':attributeに重複した値があります。',
    'doesnt_end_with' => ':attributeは:valuesで終わらない値にしてください。',
    'doesnt_start_with' => ':attributeは:valuesで始まらない値にしてください。',
    'email' => ':attributeは有効なメールアドレスにしてください。',
    'ends_with' => ':attributeは:valuesで終わる値にしてください。',
    'enum' => '選択された:attributeは無効です。',
    'exists' => '選択された:attributeは無効です。',
    'extensions' => ':attributeは以下の拡張子のいずれかである必要があります: :values。',
    'file' => ':attributeはファイルにしてください。',
    'filled' => ':attributeに値を入力してください。',
    'gt' => [
        'array' => ':attributeは:value個より多い要素にしてください。',
        'file' => ':attributeは:valueKBより大きいファイルにしてください。',
        'numeric' => ':attributeは:valueより大きい数値にしてください。',
        'string' => ':attributeは:value文字より多い文字数にしてください。',
    ],
    'gte' => [
        'array' => ':attributeは:value個以上の要素にしてください。',
        'file' => ':attributeは:valueKB以上のファイルにしてください。',
        'numeric' => ':attributeは:value以上の数値にしてください。',
        'string' => ':attributeは:value文字以上の文字数にしてください。',
    ],
    'hex_color' => ':attributeは有効な16進色コードにしてください。',
    'image' => ':attributeは画像ファイルにしてください。',
    'in' => '選択された:attributeは無効です。',
    'in_array' => ':attributeは:otherに存在しません。',
    'integer' => ':attributeは整数にしてください。',
    'ip' => ':attributeは有効なIPアドレスにしてください。',
    'ipv4' => ':attributeは有効なIPv4アドレスにしてください。',
    'ipv6' => ':attributeは有効なIPv6アドレスにしてください。',
    'json' => ':attributeは有効なJSON文字列にしてください。',
    'lowercase' => ':attributeは小文字にしてください。',
    'lt' => [
        'array' => ':attributeは:value個より少ない要素にしてください。',
        'file' => ':attributeは:valueKBより小さいファイルにしてください。',
        'numeric' => ':attributeは:valueより小さい数値にしてください。',
        'string' => ':attributeは:value文字より少ない文字数にしてください。',
    ],
    'lte' => [
        'array' => ':attributeは:value個以下の要素にしてください。',
        'file' => ':attributeは:valueKB以下のファイルにしてください。',
        'numeric' => ':attributeは:value以下の数値にしてください。',
        'string' => ':attributeは:value文字以下の文字数にしてください。',
    ],
    'mac_address' => ':attributeは有効なMACアドレスにしてください。',
    'max' => [
        'array' => ':attributeは:max個以下の要素にしてください。',
        'file' => ':attributeは:maxKB以下のファイルにしてください。',
        'numeric' => ':attributeは:max以下の数値にしてください。',
        'string' => ':attributeは:max文字以下にしてください。',
    ],
    'max_digits' => ':attributeは:max桁以下にしてください。',
    'mimes' => ':attributeは:valuesのファイルタイプにしてください。',
    'mimetypes' => ':attributeは:valuesのファイルタイプにしてください。',
    'min' => [
        'array' => ':attributeは:min個以上の要素にしてください。',
        'file' => ':attributeは:minKB以上のファイルにしてください。',
        'numeric' => ':attributeは:min以上の数値にしてください。',
        'string' => ':attributeは:min文字以上にしてください。',
    ],
    'min_digits' => ':attributeは:min桁以上にしてください。',
    'missing' => ':attributeは存在してはいけません。',
    'missing_if' => ':otherが:valueの場合、:attributeは存在してはいけません。',
    'missing_unless' => ':otherが:valuesでない場合、:attributeは存在してはいけません。',
    'missing_with' => ':valuesが存在する場合、:attributeは存在してはいけません。',
    'missing_with_all' => ':valuesがすべて存在する場合、:attributeは存在してはいけません。',
    'multiple_of' => ':attributeは:valueの倍数にしてください。',
    'not_in' => '選択された:attributeは無効です。',
    'not_regex' => ':attributeの形式が正しくありません。',
    'numeric' => ':attributeは数値にしてください。',
    'password' => 'パスワードが正しくありません。',
    'present' => ':attributeが存在しません。',
    'prohibited' => ':attributeは禁止されています。',
    'prohibited_if' => ':otherが:valueの場合、:attributeは禁止されています。',
    'prohibited_unless' => ':otherが:valuesでない場合、:attributeは禁止されています。',
    'prohibits' => ':attributeは:otherの存在を禁止しています。',
    'regex' => ':attributeの形式が正しくありません。',
    'required' => ':attributeは必須項目です。',
    'required_array_keys' => ':attributeには:valuesのキーが必要です。',
    'required_if' => ':otherが:valueの場合、:attributeは必須です。',
    'required_if_accepted' => ':otherが承認された場合、:attributeは必須です。',
    'required_unless' => ':otherが:valuesでない場合、:attributeは必須です。',
    'required_with' => ':valuesが存在する場合、:attributeは必須です。',
    'required_with_all' => ':valuesがすべて存在する場合、:attributeは必須です。',
    'required_without' => ':valuesが存在しない場合、:attributeは必須です。',
    'required_without_all' => ':valuesがすべて存在しない場合、:attributeは必須です。',
    'same' => ':attributeと:otherが一致しません。',
    'size' => [
        'array' => ':attributeは:size個の要素にしてください。',
        'file' => ':attributeは:sizeKBのファイルにしてください。',
        'numeric' => ':attributeは:sizeにしてください。',
        'string' => ':attributeは:size文字にしてください。',
    ],
    'starts_with' => ':attributeは:valuesで始まる値にしてください。',
    'string' => ':attributeは文字列にしてください。',
    'timezone' => ':attributeは有効なタイムゾーンにしてください。',
    'unique' => ':attributeは既に使用されています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'uppercase' => ':attributeは大文字にしてください。',
    'url' => ':attributeは有効なURLにしてください。',
    'ulid' => ':attributeは有効なULIDにしてください。',
    'uuid' => ':attributeは有効なUUIDにしてください。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認）',
        'current_password' => '現在のパスワード',
        'display_name' => '表示名',
        'profile_description' => 'プロフィール説明',
        'title' => 'タイトル',
        'content' => '内容',
        'main_image' => 'メイン画像',
        'media' => 'メディア',
        'pet_id' => 'ペット',
        'status' => '公開設定',
        'profile_image' => 'プロフィール画像',
        'header_image' => 'ヘッダー画像',
        'species' => '種類',
        'breed' => '品種',
        'gender' => '性別',
        'age' => '年齢',
        'estimated_age' => '推定年齢',
        'profile_description' => 'プロフィール説明',
        'shelter_id' => '保護団体',
        'website_url' => 'ウェブサイトURL',
        'instagram_url' => 'Instagram URL',
        'twitter_url' => 'Twitter URL',
        'facebook_url' => 'Facebook URL',
        'tiktok_url' => 'TikTok URL',
        'youtube_url' => 'YouTube URL',
    ],

];
