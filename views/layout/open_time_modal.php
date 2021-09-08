<div class="modal fade " id="booktable" tabindex="-2" role="dialog" aria-labelledby="booktable">
	<div class="modal-dialog modal-sm operating_time_modal" role="document" style="max-width: 90% ">
		<div class="modal-content">
	
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="table-title">
                    <h2>Operating Hours</h2>
                    <ul class="time-list" >
                        <?php
                        foreach ($calender as $key=>$day){
                            echo ('<li> <span class="week-name" style="text-align: left">'.$day['day'].'</span> <span>'.date ('h:i a',strtotime($day['open'])).' - '.date ('h:i a',strtotime($day['close'])).'</span></li>
');
                        }

                        ?>
                        <!--li> <span class="week-name" style="text-align: left">Monday</span> <span>10-12 PM</span></li>
                        <li><span class="week-name" style="text-align: left">Tuesday</span> <span>10-12 PM</span></li>
                        <li><span class="week-name" style="text-align: left">Wednesday</span> <span>10-12 PM</span></li>
                        <li><span class="week-name" style="text-align: left">Thursday</span> <span>10-12 PM</span></li>
                        <li><span class="week-name" style="text-align: left">Friday</span> <span>10-12 PM</span></li>
                        <li><span class="week-name" style="text-align: left">Saturday</span> <span>10-12 PM</span></li>
                        <li><span class="week-name" style="text-align: left">Sunday</span> <span>4-12 PM</span></li-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
