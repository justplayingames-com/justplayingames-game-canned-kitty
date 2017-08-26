@extends('layouts.app.game')

@section('title')
Canned Kitty!
@endsection

@section('js')
    <script type="text/javascript">

    window.onload = function() {

        var score = 0;
        var background_group = null;
        var cat_group = null;
        var foreground_group = null;

        var game = new Phaser.Game(
            667,
            375,
            Phaser.AUTO,
            'game',
            {
                preload: preload,
                create: create,
                render: render
            }
        );

        WebFontConfig = {

            //  'active' means all requested fonts have finished loading
            //  We set a 1 second delay before calling 'createText'.
            //  For some reason if we don't the browser cannot render the text the first time it's created.
            active: function() { game.time.events.add(Phaser.Timer.SECOND, createText, this); },

            //  The Google Fonts we want to load (specify as many as you like in the array)
            google: {
              families: ['Luckiest Guy', 'Lobster']
            }

        };


        function preload () {

            game.load.baseURL = '/assets/games/canned-kitty/';

            game.load.script('webfont', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js');

            game.load.image('background', 'img/wall-667x375.png');
            game.load.image('trashcan1', 'img/trashcan1-140x200.png')
            game.load.image('trashcan2', 'img/trashcan2-140x200.png')

            game.load.image('trashbag1', 'img/trashbag1-122x150.png')
            game.load.image('trashbag2', 'img/trashbag2-122x150.png')

            game.load.image('cat1', 'img/kitty.png');
            game.load.image('cat2', 'img/kitty2.png');

            game.load.audio('meow_a', 'wav/meow_A5.wav');
            game.load.audio('meow_b', 'wav/meow_B5.wav');
            game.load.audio('meow_c', 'wav/meow_C5.wav');
            game.load.audio('meow_d', 'wav/meow_E5.wav');
            game.load.audio('meow_e', 'wav/meow_E5.wav');
            game.load.audio('meow_f', 'wav/meow_F5.wav');
            game.load.audio('meow_g', 'wav/meow_G5.wav');

            game.load.audio('trashcan', 'wav/trashcan.wav');
        }

        function create () {

            if (!this.game.device.desktop)
            {
                game.scale.forceOrientation(true, false);
                game.scale.enterIncorrectOrientation.add(
                    function () {
                        document.getElementById('rotate').style.display = 'flex';
                        document.getElementById('game').style.display = 'none';
                        game.paused = true;
                    }
                );
                game.scale.leaveIncorrectOrientation.add(
                    function () {
                        document.getElementById('game').style.display = 'flex';
                        document.getElementById('rotate').style.display = 'none';
                        game.paused = false;
                    }
                );
            }

            game.stage.backgroundColor = '#000';

            trashcan_audio = game.add.audio('trashcan');

            meows = [
                game.add.audio('meow_a'), // 0
                game.add.audio('meow_b'), // 1
                game.add.audio('meow_c'), // 2
                game.add.audio('meow_d'), // 3
                game.add.audio('meow_e'), // 4
                game.add.audio('meow_f'), // 5
                game.add.audio('meow_g'), // 6
            ];

            songs = [
                [
                    4, 3, 2, 3, 4, 4, 4,
                    3, 3, 3, 4, 6, 6,
                    4, 3, 2, 3, 4, 4, 4,
                    4, 3, 3, 4, 3, 2
                ],
                [
                    2, 2, 6, 6, 0, 0, 6,
                    5, 5, 4, 4, 3, 3, 2,
                    6, 6, 5, 5, 4, 4, 3,
                    6, 6, 5, 5, 4, 4, 3,
                    2, 2, 6, 6, 0, 0, 6,
                    5, 5, 4, 4, 3, 3, 2
                ]
            ];
            song = 0;
            note = 0;

            cats = [
                {
                    image: 'cat1',
                    anchor: {x: 0.5, y: 0},
                    paths: [
                        {
                            from: {x: 100, y: 240},
                            to: {x: 20, y: 240}
                        },
                        {
                            from: {x: 100, y: 240},
                            to: {x: 230, y: 240}
                        },
                        {
                            from: {x: 170, y: 240},
                            to: {x: 170, y: 100}
                        },
                        {
                            from: {x: 300, y: 240},
                            to: {x: 300, y: 100}
                        },
                        {
                            from: {x: 290, y: 240},
                            to: {x: 240, y: 240}
                        },
                        {
                            from: {x: 400, y: 240},
                            to: {x: 400, y: 140}
                        },
                        {
                            from: {x: 540, y: 240},
                            to: {x: 540, y: 100}
                        }, 
                        {
                            from: {x: 540, y: 240},
                            to: {x: 490, y: 240}
                        },
                        {
                            from: {x: 550, y: 240},
                            to: {x: 630, y: 240}
                        }
                    ]
                },

                {
                    image: 'cat2',
                    anchor: {x: 0.25, y: 0},
                    paths: [
                        {
                            from: {x: 100, y: 240},
                            to: {x: 20, y: 240}
                        },
                        {
                            from: {x: 100, y: 240},
                            to: {x: 230, y: 240}
                        },
                        {
                            from: {x: 170, y: 240},
                            to: {x: 170, y: 100}
                        },
                        {
                            from: {x: 300, y: 240},
                            to: {x: 300, y: 100}
                        },
                        {
                            from: {x: 290, y: 240},
                            to: {x: 240, y: 240}
                        },
                        {
                            from: {x: 400, y: 240},
                            to: {x: 400, y: 140}
                        },
                        {
                            from: {x: 540, y: 240},
                            to: {x: 540, y: 100}
                        }, 
                        {
                            from: {x: 540, y: 240},
                            to: {x: 490, y: 240}
                        },
                        {
                            from: {x: 550, y: 240},
                            to: {x: 630, y: 240}
                        }
                    ]
                }
            ]

            createBackground();

            cat_group = game.add.group();
            foreground_group = game.add.group();

            create_trash();
            create_cat();
        }

        function createBackground() {
            background_group = game.add.group();

            var background = background_group.create(
                0,
                0,
                'background'
            );

            background.anchor.setTo(0, 0);
        }

        function createText() {
            createTitleText();
            createScoreText();
        }

        function createTitleText() {
            titleText = game.add.text(
                16, 16, 'Canned Kitty', {fontSize: '54px'}
            );

            titleText.font = 'Luckiest Guy';

            grd = titleText.context.createLinearGradient(0, 0, 0, titleText.canvas.height);
            grd.addColorStop(0, '#FFF');
            grd.addColorStop(1, '#F0F');
            titleText.fill = grd;

            titleText.align = 'center';
            titleText.stroke = '#000000';
            titleText.strokeThickness = 2;
            titleText.setShadow(5, 5, 'rgba(0,0,0,0.5)', 5);
        }

        function createScoreText() {
            scoreText = game.add.text(500, 40, 'Score: 0', { fontSize: '32px', fill: '#FFF' });
            scoreText.font = 'Lobster'
            scoreText.setShadow(5, 5, 'rgba(0,0,0,0.5)', 5);
        }

        function render() {
            // render_debug();
        }

        function render_debug() {
            game.debug.text( 'innerWidth: ' + window.innerWidth , 10, 100);
            game.debug.text( 'innerHeight: ' + window.innerHeight , 10, 112);
            game.debug.text( 'devicePixelRatio: ' + window.devicePixelRatio , 10, 124);
            game.debug.text( 'isFullScreen: ' + game.scale.isFullScreen, 10, 136);
            game.debug.text( 'scaleMode: ' + game.scale.scaleMode, 10, 148);

            game.debug.text( 'camera.height: ' + game.camera.height, 10, 160);
            game.debug.text( 'camera.width: ' + game.camera.width, 10, 172);
        }

        function create_cat() {
        
            cat = cats[Math.floor(Math.random() * cats.length)];

            path = cat.paths[Math.floor(Math.random() * cat.paths.length)];

            var cat_sprite = cat_group.create(
                path.from.x, 
                path.from.y, 
                cat.image
            );

            cat_sprite.anchor = cat.anchor;
            cat_sprite.scale.x = Phaser.Utils.randomChoice(-1.0, 1.0);

            cat_sprite.inputEnabled = true;

            game.add.tween(cat_sprite).to( 
                path.to, 
                4000, 
                Phaser.Easing.Elastic.Out, 
                true
            );

            cat_sprite.events.onInputDown.add(
                function() {

                    meow = meows[songs[song][note]];
                    note = note + 1

                    if (note >= songs[song].length) {
                        note = 0;

                        song = (song + 1) % songs.length;
                    }

                    meow.play();
                    cat_sprite.kill();

                    score += 1;
                    scoreText.text = 'Score: ' + score;

                    create_cat();
                },
                cat_sprite
            );
        }

        function create_trash()
        {
            var trash = [
                foreground_group.create(100, 155, 'trashcan1'),
                foreground_group.create(20, 225, 'trashbag2'),
                foreground_group.create(360, 200, 'trashbag1'),
                foreground_group.create(250, 170, 'trashcan2'),
                foreground_group.create(490, 165, 'trashcan1'),
            ];

            trash[0].inputEnabled = true;
            trash[0].tween = null;
            trash[0].events.onInputDown.add(
                function() {
                    if (trash[0].tween) {
                        trash[0].tween.stop();
                        trash[0].tween = null;
                        trash[0].position.set(100, 155);
                    }

                    trashcan_audio.play();
                    trash[0].tween = game.add.tween(trash[0]).to( {x: 102, y: 155}, 50, Phaser.Easing.Elastic.Out, true, 0, 1, true);
                }
            );

            trash[1].inputEnabled = true;
            trash[1].tween = null;
            trash[1].events.onInputDown.add(
                function() {
                    if (trash[1].tween) {
                        trash[1].tween.stop();
                        trash[1].tween = null;
                        trash[1].position.set(20, 225);
                    }

                    trash[1].tween = game.add.tween(trash[1]).to( {x: 20, y: 227}, 50, Phaser.Easing.Elastic.Out, true, 0, 1, true);
                }
            );

            trash[2].inputEnabled = true;
            trash[2].tween = null;
            trash[2].events.onInputDown.add(
                function() {
                    if (trash[2].tween) {
                        trash[2].tween.stop();
                        trash[2].tween = null;
                        trash[2].position.set(360, 200);
                    }

                    trash[2].tween = game.add.tween(trash[2]).to( {x: 360, y: 202}, 50, Phaser.Easing.Elastic.Out, true, 0, 1, true);
                }
            );

            trash[3].inputEnabled = true;
            trash[3].tween = null;
            trash[3].events.onInputDown.add(
                function() {
                    if (trash[3].tween) {
                        trash[3].tween.stop();
                        trash[3].tween = null;
                        trash[3].position.set(250, 170);
                    }

                    trashcan_audio.play();
                    trash[3].tween = game.add.tween(trash[3]).to( {x: 253, y: 170}, 50, Phaser.Easing.Elastic.Out, true, 0, 1, true);
                }
            );

            trash[4].inputEnabled = true;
            trash[4].tween = null;
            trash[4].events.onInputDown.add(
                function() {
                    if (trash[4].tween) {
                        trash[4].tween.stop();
                        trash[4].tween = null;
                        trash[4].position.set(490, 165);
                    }

                    trashcan_audio.play();
                    trash[4].tween = game.add.tween(trash[4]).to( {x: 492, y: 165}, 50, Phaser.Easing.Elastic.Out, true, 0, 1, true);
                }
            );
        }
    };
    </script>
@endsection
