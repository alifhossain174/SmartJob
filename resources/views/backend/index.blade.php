@extends('backend.master')

@section('content')
    <div class="container-fluid">
        @if(Auth::user()->id == 1)
        <div class="row">
            <div class="col-lg-6">
                <div class="card mt-4">
                    <div class="card-header bg-info text-white pt-2 pb-2">
                        Welcome to the Dashboard
                    </div>
                    <div class="card-body pt-3 pb-3" style="height: 225px; border-left: 1px solid #46BC53 !important; border-bottom: 1px solid #46BC53 !important;">
                        Hey {{Auth::user()->name}}, Enjoy the best experiance.<br><br>
                        @php
                            $user_data = DB::table('users')->where('id', Auth::user()->id)->first();
                        @endphp
                        Account Created : {{$user_data->created_at}} <br>
                        Current Browser :
                        @php
                            function getBrowser()
                            {
                                $u_agent = $_SERVER['HTTP_USER_AGENT'];
                                $bname = 'Unknown';
                                $platform = 'Unknown';
                                $version= "";

                                //First get the platform?
                                if (preg_match('/linux/i', $u_agent)) {
                                    $platform = 'linux';
                                }
                                elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                                    $platform = 'mac';
                                }
                                elseif (preg_match('/windows|win32/i', $u_agent)) {
                                    $platform = 'windows';
                                }

                                // Next get the name of the useragent yes seperately and for good reason
                                if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
                                {
                                    $bname = 'Internet Explorer';
                                    $ub = "MSIE";
                                }
                                elseif(preg_match('/Firefox/i',$u_agent))
                                {
                                    $bname = 'Mozilla Firefox';
                                    $ub = "Firefox";
                                }
                                elseif(preg_match('/Chrome/i',$u_agent))
                                {
                                    $bname = 'Google Chrome';
                                    $ub = "Chrome";
                                }
                                elseif(preg_match('/Safari/i',$u_agent))
                                {
                                    $bname = 'Apple Safari';
                                    $ub = "Safari";
                                }
                                elseif(preg_match('/Opera/i',$u_agent))
                                {
                                    $bname = 'Opera';
                                    $ub = "Opera";
                                }
                                elseif(preg_match('/Netscape/i',$u_agent))
                                {
                                    $bname = 'Netscape';
                                    $ub = "Netscape";
                                }

                                // finally get the correct version number
                                $known = array('Version', $ub, 'other');
                                $pattern = '#(?<browser>' . join('|', $known) .
                                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
                                if (!preg_match_all($pattern, $u_agent, $matches)) {
                                    // we have no matching number just continue
                                }

                                // see how many we have
                                $i = count($matches['browser']);
                                if ($i != 1) {
                                    //we will have two since we are not using 'other' argument yet
                                    //see if version is before or after the name
                                    if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                                        $version= $matches['version'][0];
                                    }
                                    else {
                                        $version= $matches['version'][1];
                                    }
                                }
                                else {
                                    $version= $matches['version'][0];
                                }

                                // check if we have a number
                                if ($version==null || $version=="") {$version="?";}

                                return array(
                                    'userAgent' => $u_agent,
                                    'name'      => $bname,
                                    'version'   => $version,
                                    'platform'  => $platform,
                                    'pattern'    => $pattern
                                );
                            }

                            // now try it
                            $ua=getBrowser();
                            $yourbrowser = $ua['name'];
                            echo $yourbrowser;
                        @endphp
                        <br>

                        Server OS :  @php echo PHP_OS; @endphp

                        <br>

                        IP Address :

                        @php
                            function getIPAddress() {
                            //whether ip is from the share internet
                            if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
                                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                                }
                            //whether ip is from the proxy
                            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                            }
                            //whether ip is from the remote address
                                else{
                                        $ip = $_SERVER['REMOTE_ADDR'];
                                }
                                return $ip;
                            }
                            $ip = getIPAddress();
                            echo $ip;
                        @endphp
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mt-4">
                    <div class="card-header bg-info text-white pt-2 pb-2">
                        Send Notification to All Users
                    </div>
                    <div class="card-body pt-2 pb-2" style="height: 225px; border-left: 1px solid #46BC53 !important; border-bottom: 1px solid #46BC53 !important;">
                        <form action="{{url('send/notification')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Notification Title" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description" placeholder="Notification Description" required>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn rounded btn-info">Send Notification</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mt-1">
                    <div class="card-header bg-info text-white pt-2 pb-2">
                        View Website Visit Count
                    </div>
                    <div class="card-body pt-2 pb-2" style="height: 225px; border-left: 1px solid #46BC53 !important; border-bottom: 1px solid #46BC53 !important;">
                        <form id="websiteVisitCountFrom" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>From</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label>To</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" id="getWesbiteCountBtn" class="btn rounded btn-info">Get Visit Count</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection


@section('footer_js')
    <script type="text/javascript">
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#getWesbiteCountBtn').click(function(e) {

                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                if(start_date == '' || end_date == ''){
                    toastr.error("Please Select Date Range");
                    return false;
                }

                e.preventDefault();
                $(this).html('Counting...');
                $.ajax({
                    data: $('#websiteVisitCountFrom').serialize(),
                    url: "{{ url('/count/website/visit') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        // $('#productForm').trigger("reset");
                        $(this).html('Get Visit Count');
                        $('#getWesbiteCountBtn').html('Visit Count: '+data.data.toLocaleString());
                        toastr.success("Fetched Successfully");
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        toastr.error("Something Went Wrong");
                        $('#getWesbiteCountBtn').html('Try Again');
                    }
                });
            });

        });
    </script>
@endsection
