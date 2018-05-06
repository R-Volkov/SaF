<?php 
use kartik\datetime\DateTimePicker;
?>

<div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Забанить пользователя?</h4>
            </div>
        <div class="modal-body">
            <p> Вы собираетесь забанить пользователя <span class="username"></span> (id<span class="userId"></span>). Укажите дату разбана, или выберете предустановленный временной интервал начиная с текущего времени и нажмите "Забанить временно". Для вечного бана нажмите "Забанить навсегда".</p>
            <p></p>
            
            <?php 
                echo '<label class="control-label">Enter the time</label>';
                echo DateTimePicker::widget([
                    'name' => 'time_for_ban',
                    'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'startDate' => date('o-m-d H:i:s')
                    ],
                    'options' => [
                        'id' => 'time_for_ban',
                    ]
                ]);
             ?>
             <input type="text" name="date_for_ban" hidden="hidden">
        </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <!-- <button type="button" class="btn btn-primary banOnTime">Забанить временно</button> -->
                <!-- <button type="button" class="btn btn-danger">Забанить навсегда</button> -->
                <a href="" class="userId btn btn-primary temporaryBan">Забанить временно</a>
                <a href="" class="userId btn btn-danger banForever">Забанить навсегда</a>
            </div>
        </div>
    </div>
</div>