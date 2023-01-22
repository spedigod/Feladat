<?php 

# Egyetemek es kovetelmenyeik
/*
A tomboket ugy epitettem fel, hogy konnyeden bovithetok legyenek
*/
$egyetemek = [
    'ELTE' => [
        'IK' => [
            'Programtervező informatikus' => [
                'kovetelmenyek' => [
                    'kotelezo-targy' => [
                        'matematika' => 'kozep'
                    ],
                    'kotelezoen-valaszthato' => ['biologia', 'fizika', 'informatika', 'kemia']
                ]
            ]
        ]
    ],
    'PPKE' => [
        'BTK' => [
            'Anglisztika' => [
                'kovetelmenyek' => [
                    'kotelezo-targy' => [
                        'angol' => 'emelt'
                    ],
                    'kotelezoen-valaszthato' => ['biologia', 'fizika', 'informatika', 'kemia']
                ]
            ]
        ]
    ]
];