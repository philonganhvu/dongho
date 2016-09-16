<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\web\View;

$js_global_variables = '
    $.ajaxSetup({
    data: ' . \yii\helpers\Json::encode([
        \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
    ]) . '
    });' . PHP_EOL;
$this->registerJs($js_global_variables, yii\web\View::POS_HEAD, 'js_global_variables');

$this->registerJs("var imagePath = ". json_encode($image_path).";",View::POS_HEAD);
$this->title = 'LƯỢC ĐỒ TỘC PHẢ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div><hr><br /></div>

    <div id="sample">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <h3>Lược đồ trích dẫn</h3>
        <div id="myDiagramDiv" style="background-color: #929292; border: solid 1px black; width: 100%; height: 550px"></div>
        <p><button id="zoomToFit">Zoom to Fit</button> <button id="centerRoot">Center on root</button></p>
    </div>
</div>


<script id="code">
    function init() {
        if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
        var $ = go.GraphObject.make;  // for conciseness in defining templates

        myDiagram =
            $(go.Diagram, "myDiagramDiv",  // must be the ID or reference to div
                {
                    "toolManager.hoverDelay": 100,  // 100 milliseconds instead of the default 850
                    allowCopy: false,
                    layout:  // create a TreeLayout for the family tree
                       $(go.TreeLayout,
                            {
                                angle: 90,
                                nodeSpacing: 10,
                                layerSpacing: 40,
                                layerStyle: go.TreeLayout.LayerUniform
                             }),
                    "undoManager.isEnabled": true // enable undo & redo

                });

        var bluegrad = '#90CAF9';
        var pinkgrad = '#F48FB1';

        // Set up a Part as a legend, and place it directly on the diagram
       /* myDiagram.add(
            $(go.Part, "Table",
                { position: new go.Point(900, 10), selectable: false },
                $(go.Panel, "Horizontal",
                    { row: 1, alignment: go.Spot.Left },
                    $(go.Shape, "Rectangle",
                        { desiredSize: new go.Size(30, 30), fill: bluegrad, margin: 5 }),
                    $(go.TextBlock, "Males",
                        { font: "700 13px Droid Serif, sans-serif" })
                ),  // end row 1
                $(go.Panel, "Horizontal",
                    { row: 2, alignment: go.Spot.Left },
                    $(go.Shape, "Rectangle",
                        { desiredSize: new go.Size(30, 30), fill: pinkgrad, margin: 5 }),
                    $(go.TextBlock, "Females",
                        { font: "700 13px Droid Serif, sans-serif" })
                )  // end row 2
            ));*/

        // get tooltip text from the object's data
        function tooltipTextConverter(person) {
            var str = "";
            str += "Năm sinh: " + person.birthYear;
            if (person.deathYear !== undefined) str += "\n\nNăm mất: " + person.deathYear;
            if (person.vo !== undefined) str += "\n\nVợ: " + person.vo;
            if (person.chong !== undefined) str += "\n\nChồng: " + person.chong;
            return str;
        }

        // This converter is used by the Picture.
        function findHeadShot(key) {
            if (key < 0 || key > 17) return imagePath +"images/HSnopic.png"; // There are only 16 images on the server
            return imagePath +"images/HS" + key + ".png"
        }

        // define tooltips for nodes
        var tooltiptemplate =
            $(go.Adornment, "Auto",
                $(go.Shape, "Rectangle",
                    { fill: "whitesmoke", stroke: "red" }),
                $(go.TextBlock,
                    { font: "bold 8pt Helvetica, bold Arial, sans-serif",
                        wrap: go.TextBlock.WrapFit,
                        margin: 5 },
                    new go.Binding("text", "", tooltipTextConverter))
            );

        // define Converters to be used for Bindings
        function genderBrushConverter(gender) {
            if (gender === "M") return bluegrad;
            if (gender === "F") return pinkgrad;
            return "orange";
        }

        // replace the default Node template in the nodeTemplateMap
        myDiagram.nodeTemplate =
            $(go.Node, "Auto",
                { deletable: false, toolTip: tooltiptemplate },
                new go.Binding("text", "name"),
                $(go.Shape, "Rectangle",
                    { fill: "lightgray",
                        stroke: null, strokeWidth: 0,
                        stretch: go.GraphObject.Fill,
                        alignment: go.Spot.Center },
                    new go.Binding("fill", "gender", genderBrushConverter)),
                $(go.Panel, "Horizontal",
                    $(go.Picture,
                        {
                            name: 'Picture',
                            desiredSize: new go.Size(39, 50),
                            margin: new go.Margin(6, 8, 6, 10),
                        },
                        new go.Binding("source", "key", findHeadShot)),
                    $(go.TextBlock,
                        { font: "700 12px Droid Serif, sans-serif",
                            textAlign: "start",
                            margin: 10, maxSize: new go.Size(100, NaN) },
                        new go.Binding("text", "name")),
                    {
                        click: function(e, obj) { alert(obj.part.data.key);window.open('http://google.com.vn','_blank'); }
                    }
                )
            );

        // define the Link template
        myDiagram.linkTemplate =
            $(go.Link,  // the whole link panel
                { routing: go.Link.Orthogonal, corner: 5, selectable: false },
                $(go.Shape, { strokeWidth: 1, stroke: 'red' }));  // the gray link shape

        // here's the family data
        loadData();
        console.log(nodeDataArray);
        // create the model for the family tree
        myDiagram.model = new go.TreeModel(nodeDataArray);

        document.getElementById('zoomToFit').addEventListener('click', function() {
            myDiagram.zoomToFit();
        });

        document.getElementById('centerRoot').addEventListener('click', function() {
            myDiagram.scale = 1;
            myDiagram.scrollToRect(myDiagram.findNodeForKey(0).actualBounds);
        });
    }
    //load mac dinh
    function loadData() {
        csrfToken = $('meta[name="csrf-token"]').attr("content");
        var form_data = {
            member_id: 1,
            depth: 8,
            _csrf: yii.getCsrfToken()
        };
        //console.log(form_data);
        $.ajax({
            type: "POST",
            url: '<?=Yii::$app->urlManager->createUrl('graph/ajaxloadmembers');?>',
            dataType: 'json',
            data: form_data,
            async: false,
            success: function (jsonData) {
                if ($.isEmptyObject(jsonData))
                    nodeDataArray = [];
                else
                    nodeDataArray = jsonData;
            },
            error: function (xhr, tStatus, e) {
                if (!xhr) {
                    alert(" We have an error ");
                    alert(tStatus + "   " + e.message);
                } else {
                    alert("else: " + e.message); // the great unknown
                }
            }
        });
    }
</script>
