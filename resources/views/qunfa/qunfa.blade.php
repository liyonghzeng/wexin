<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src="/js/jquery.js"></script>

</head>
<body class="skin-blue-light sidebar-mini sidebar-collapse">
<div class="wrapper">
    <header class="main-header">
        <table >
            <tr>
                <td></td>
                <td>id</td>
                <td>openid</td>
                <td>名称</td>
                <td>地址</td>
            </tr>
            @foreach($data as $k=>$v)
                <tr>
                    <td><input type="checkbox" class="check"></td>
                <td>{{$v->wx_id}}</td>
                <td>{{$v->openid}}</td>
                <td>{{$v->nickname}}</td>
                <td>{{$v->headimgurl}}</td>
                </tr>
            @endforeach
        </table>

    </header>


    <aside class="main-sidebar"></aside>
        <di class="content-wrapper" id="pjax-container" style="min-height: 175px;">

        </di>
</div>
    <from action="javescript:;">
        <textarea name="" id="nr" cols="30" rows="10" placeholder="将要发送的推送黑用户的内容"></textarea><br />
        <input type="submit" id="tuSong">
    </from>
</body>
</html>
<script>
    $(function(){
        // $('#tuSong').click(function(){
        //     $("#check").each(funtion(k,v){
        //
        //})
        $('#tuSong').click(function(){
            var o=$("#nr").val();
            // alert(o);
            var i ='';
            $( ".check" ).each(function ( index, domEle) {
                        if ( $(this).prop( "checked" ) == true ) {

                           i += $(this).parent().next().next().text()+',';

                        }
            })
            $.post(
                "dispose",
                { nr: o, penid: i },
                    function(data){
                     if($data = 'ok'){
                         history.go(0);
                     }
                },'json'
            );
        })
    });
</script>