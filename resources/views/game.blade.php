<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
        <title>Canned Kitty!</title>
        <script src="//cdn.jsdelivr.net/phaser/2.6.2/phaser.min.js"></script>
        <style media="screen" type="text/css">

            body {
                margin: 0 !important
            };

        </style>

    </head>
    <body>

    <script type="text/javascript">

    window.onload = function() {

        var score = 0;
        var background_group = null;
        var cat_group = null;
        var foreground_group = null;

        var game = new Phaser.Game(
            window.innerWidth * window.devicePixelRatio,
            window.innerHeight * window.devicePixelRatio,
            Phaser.CANVAS,
            '',
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

            game.load.image('cat', 'img/kitty.png');

            game.load.audio('meow_a', 'wav/meow_A5.wav');
            game.load.audio('meow_b', 'wav/meow_B5.wav');
            game.load.audio('meow_c', 'wav/meow_C5.wav');
            game.load.audio('meow_d', 'wav/meow_E5.wav');
            game.load.audio('meow_e', 'wav/meow_E5.wav');
            game.load.audio('meow_f', 'wav/meow_F5.wav');
            game.load.audio('meow_g', 'wav/meow_G5.wav');
        }

        function create () {

            game.stage.backgroundColor = '#000';

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

            paths = [
                {
                    from: {x: 100, y: 240},
                    to: {x: 0, y: 240}
                },
                {
                    from: {x: 100, y: 240},
                    to: {x: 200, y: 240}
                },
                {
                    from: {x: 100, y: 240},
                    to: {x: 100, y: 100}
                },
                {
                    from: {x: 250, y: 240},
                    to: {x: 250, y: 120}
                },

                {
                    from: {x: 250, y: 240},
                    to: {x: 200, y: 240}
                },
                {
                    from: {x: 400, y: 240},
                    to: {x: 400, y: 180}
                },

                {
                    from: {x: 500, y: 240},
                    to: {x: 500, y: 100}
                },

                {
                    from: {x: 500, y: 240},
                    to: {x: 450, y: 240}
                },

                {
                    from: {x: 500, y: 240},
                    to: {x: 580, y: 240}
                },
            ];

            background_group = game.add.group();

            var background = background_group.create(
                0,
                0,
                'background'
            );

            background.anchor.setTo(0, 0);


            cat_group = game.add.group();

            foreground_group = game.add.group();

            var trash = [
                foreground_group.create(100, 155, 'trashcan1'),
                foreground_group.create(20, 225, 'trashbag2'),
                foreground_group.create(360, 200, 'trashbag1'),
                foreground_group.create(250, 170, 'trashcan2'),
                foreground_group.create(480, 165, 'trashcan1'),
            ];

            create_cat();

        }

        function createText() {
            titleText = game.add.text(
                16, 16, 'Canned Kitty', {fontSize: '54px'});

            titleText.font = 'Luckiest Guy';
            //titleText.size = 72;

            grd = titleText.context.createLinearGradient(0, 0, 0, titleText.canvas.height);
            grd.addColorStop(0, '#FFF');
            grd.addColorStop(1, '#F0F');
            titleText.fill = grd;

            titleText.align = 'center';
            titleText.stroke = '#000000';
            titleText.strokeThickness = 2;
            titleText.setShadow(5, 5, 'rgba(0,0,0,0.5)', 5);

            scoreText = game.add.text(500, 40, 'Score: 0', { fontSize: '32px', fill: '#FFF' });
            scoreText.font = 'Lobster'
            scoreText.setShadow(5, 5, 'rgba(0,0,0,0.5)', 5);
        }

        function render() {
        }

        function create_cat() {

            path = paths[Math.floor(Math.random() * paths.length)];

            var cat = cat_group.create(path.from.x, path.from.y, 'cat');

            cat.inputEnabled = true;

            game.add.tween(cat).to( path.to, 4000, Phaser.Easing.Elastic.Out, true);

            cat.events.onInputDown.add(
                function() {

                    // meow = meows[Math.floor(Math.random() * meows.length)];

                    meow = meows[songs[song][note]];
                    note = note + 1

                    if (note >= songs[song].length) {
                        note = 0;

                        song = (song + 1) % songs.length;
                    }

                    meow.play();
                    cat.kill();

                    score += 1;
                    scoreText.text = 'Score: ' + score;

                    create_cat();
                },
                cat
            );
        }
    };

    </script>

    </body>
</html>
