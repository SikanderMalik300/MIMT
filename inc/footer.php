
      <!-- footer content -->
        <footer>
          <div class="pull-right">
              <strong>Copyright © <?php echo date('Y') ?>.</strong>
              <b><?php echo $_settings->info('short_name') ?> </b> v1.0
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
   


    <!-- ./wrapper -->
    <script>
  $(document).ready(function(){
    window.viewer_modal = function($src = ''){
      start_loader()
      var t = $src.split('.')
      t = t[1]
      if(t =='mp4'){
        var view = $("<video src='"+$src+"' controls autoplay></video>")
      }else{
        var view = $("<img src='"+$src+"' />")
      }
      $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
      $('#viewer_modal .modal-content').append(view)
      $('#viewer_modal').modal({
        show:true,
        backdrop:'static',
        keyboard:false,
        focus:true
      })
      end_loader()
    }
    window.uni_modal = function($title = '' , $url='',$size=""){
      start_loader()
      $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
          if(resp){
            $('#uni_modal .modal-title').html($title)
            $('#uni_modal .modal-body').html(resp)
            if($size != ''){
                $('#uni_modal .modal-dialog').addClass($size+'  modal-dialog-centered')
            }else{
                $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md modal-dialog-centered")
            }
            $('#uni_modal').modal({
              show:true,
              backdrop:'static',
              keyboard:false,
              focus:true
            })
            end_loader()
          }
        }
      })
    }
    window._conf = function($msg='',$func='',$params = []){
       $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
       $('#confirm_modal .modal-body').html($msg)
       $('#confirm_modal').modal('show')
    }
  })
</script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      // $.widget.bridge('uibutton', $.ui.button)
    </script>
    
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/select2.full.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sweetalert2/sweetalert2.all.min.js"></script>
    
    <!-- Bootstrap -->
    <script src="css/bootstrap/js/bootstrap.bundle.min.js"></script>


    <!-- Custom Theme Scripts -->
    <script src="js/custom.js"></script>
	
