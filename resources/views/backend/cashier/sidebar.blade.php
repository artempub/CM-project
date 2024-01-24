<div id="mySidenav" class="sidenav" style="right:-260px;">
  <header id="right_sidebar_header">
    <h2 id="sidebar_title">
      Settings
    </h2>
  </header>
  <content id="right_sidebar_content">
    <div id="sidebar_main">
        <button type="button" class="sidebar_collapsible"><span style="font-weight:700">Shift</span></button>
        <div class="collapse_content" style="display:block">
          <label for="sidebar_time" id="label_time">Shift time:</label>
          <div class="grid-time input-group date" id="sidebar_time">
            <div>
              <input id="sidebar_set_time" type="text" name="time" class="form-control">
              <div class="input-group-addon" id="time-picki-from" style="cursor: pointer;">
                  <div class="img-icon-clock"></div>
              </div>
            </div>
          </div>
          <br/>
          <div class="btn-group">
            <button type="button" class="btn" onclick="getData('yesterday')" id="btn_yes">Yesterday</button>
            <button type="button" class="btn btn-primary" onclick="getData('today')" id="btn_today">Today</button>
          </div>
        </div>
        <div class="btn-group form-control" style="padding:0;">
          <button type="button" class="btn" id="btn_save" onclick="save()" style="font: size 12.5px;width:50%;">Save</button>
          <button type="button" class="btn" id="btn_cancel" style="font: size 12.5px;width:50%;" onclick="closeRightSide()">Close</button>
        </div>
        <br/>
    </div>
  </content>
  <footer id="right_sidebar_footer">
    <div id="loading"></div>
  </footer>
</div>

<script>
  window.getTransaction = function(){
        let sidebarTime = $("#sidebar_set_time").val();
    }
    function getStrDate(date){
        date=date.split('/');
        if(date[0]<10)date[0]='0'+date[0];
        if(date[1]<10)date[1]='0'+date[1];
        return date[2]+'-'+date[0]+'-'+date[1];
    }
    function getStrTime(time){
        time=time.split(' ');
        let pm = Boolean(time[1]=='PM')
        time=time[0].split(':');
        if(pm){
            time[0]=12+Number(time[0]);
        }
        if(time[0]==12 || time[0]==24)time[0]-=12;
        return time[0]+":"+time[1];
    }
    $(function () {
        var date = new Date();
        var cur_date = (date.getMonth() + 1) + "/" + date.getDate()+"/"+ date.getFullYear() ;
        $("#sidebar_set_time").val('00:00');
        $("#sidebar_set_time").timepicki({
            reset: true,
            show_meridian:false,
            min_hour_value:0,
            max_hour_value:23,
            overflow_minutes:true,
            increase_direction:'up',
            disable_keyboard_mobile: true,
            start_time:[00,00]
        });
    })
  </script>

  <script>

    function getData(type){
      if(type === "yesterday"){
        document.getElementById('btn_today').className = "btn";
        var clsName = document.getElementById('btn_yes').className;
        clsName = clsName.replace(" btn-primary","");
        document.getElementById('btn_yes').className += " btn-primary";
      }else{
        document.getElementById('btn_yes').className = "btn";
        var clsName = document.getElementById('btn_today').className;
        clsName = clsName.replace(" btn-primary","");
        document.getElementById('btn_today').className += " btn-primary";
      }
    }
    function save(){
      document.getElementById('btn_cancel').className = "btn";
      var clsName = document.getElementById('btn_save').className;
      clsName = clsName.replace(" btn-primary","");
      document.getElementById('btn_save').className += " btn-primary";
      let sidebarTime = $("#sidebar_set_time").val().substr(0,5);
      var time = sidebarTime.split(":");
      $('#timeFrom').val($("#sidebar_set_time").val());
      $("#timeFrom").timepicki({
        reset:true,
        show_meridian:false,
        min_hour_value:0,
        max_hour_value:23,
        overflow_minutes:true,
        increase_direction:'up',
        disable_keyboard_mobile: true,
        start_time:time
      })
      var yesClass = document.getElementById('btn_yes').className;
      var dt = new Date();
      var year = dt.getFullYear();
      var mon = (dt.getMonth()+1);
      var date = dt.getDate();
      if(yesClass.includes("btn-primary")){
        $('#dateFrom').val(mon+"/"+(date-1)+"/"+year);
      }else{
        $('#dateFrom').val(mon+"/"+date+"/"+year);
      }
      getTransaction();
      closeRightSide();
    }
    function setLoading(state){
      if(state){
        document.getElementById("loading").style.display = "";
      }else{
        document.getElementById("loading").style.display = "block";
      }
    }
  </script>