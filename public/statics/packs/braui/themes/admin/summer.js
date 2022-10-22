require(['pixi' , 'pixi-particles' , 'pixi-filters'] , function (PIXI) {

    const app = new PIXI.Application({
        resizeTo: window,
        autoDensity: true,
        width: 800, height: 600,
        backgroundColor: 0x000000,
        resolution: window.devicePixelRatio || 1,
        transparent: false,
        backgroundAlpha : 0
    });

    document.getElementById('container').appendChild(app.view);

    const loader = PIXI.Loader.shared;
    loader
        .add('backgroundImage' , "https://img.zcool.cn/community/01c9075ed6fd2fa801206621e9870e.jpg@3000w_1l_2o_100sh.jpg")
        .load(setup)

    function setup(loader , resource){
        const bg = PIXI.Sprite.from(resource.backgroundImage.texture)
        bg.anchor.set(0.5)
        bg.x = app.renderer.width / 2
        bg.y = app.renderer.height / 2

        const container = new PIXI.Container()
        container.addChild(bg)

        app.stage.addChild(container)
        const style = new PIXI.TextStyle({
            fontFamily : 'Montserrat' ,
            fontSize : 20 + window.innerWidth * 0.06 ,
            fill : '#ffffff' ,
            dropShadow : true ,
            dropShadowDistance : 2,
            dropShadowAngle : Math.PI / 2,
            dropShadowColor : '#000000'
        })

        // window.addEventListener('resize' , function (){
        //     style.fontSize = 20 + window.innerWidth * 0.06
        // })
        const myText = new PIXI.Text("Summer" , style)
        // container.addChild(myText)

        const displacementSprite = PIXI.Sprite.from("/statics/pixi/water.jpg");
        const displacementFilter = new PIXI.filters.DisplacementFilter(displacementSprite)
        displacementSprite.scale.set(5)

        displacementSprite.texture.baseTexture.wrapMode = PIXI.WRAP_MODES.REPEAT

        container.addChild(displacementSprite)

        container.filters =  [displacementFilter]

        var rainFilters = []
        for (let i = 0; i < 10; i++) {
            var filter = new PIXI.filters.ShockwaveFilter([
                Math.random() * app.screen.width ,
                Math.random() * app.screen.height ,
            ] , {
                amplitude : 40 + Math.floor( Math.random() * 40 ),
                wavelength : 20 + Math.floor( Math.random() * 25 ),
                speed : 150 +   Math.floor( Math.random() * 80 ) ,
                radius : 25 +   Math.floor( Math.random() * 75 )
            } , 0)
            container.filters.push(filter)
            rainFilters.push(filter)
        }

        app.ticker.add(function () {
            displacementSprite.x ++;
            if(displacementSprite.x >displacementSprite.width ){
                displacementSprite.x = 0
            }

            for (let i = 0; i < 10; i++) {
                createRainDrops(rainFilters[i] , i / 10 + Math.random());
            }
        });

        function  createRainDrops(filter , resetTime){
            filter.time += 0.01;

            if (filter.time > resetTime){
                filter.time = 0;
                filter.center = [
                    Math.random() * app.screen.width ,
                    Math.random() * app.screen.height ,
                ]
            }
        }
    }

});
