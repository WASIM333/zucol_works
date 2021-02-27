<?php
	// ob_start();
	// error_reporting(0);
	// session_start();
	// $company_id=$_SESSION['company_id'];
	// $admin_id = $_SESSION["admin_id"];
	// if(!isset($_SESSION["admin_id"]))
	// {
	// 	header('location:../logout.php');
	// }
	// date_default_timezone_set('Asia/Calcutta');
	// $da = date("Y-m-d");
	// $ti = date("h:i:s");
	// $date_time=$da." ".$ti;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BTCONNECT - ADMIN</title>
	<link rel="shortcut icon" href="https://www.bthawk.appexperts.net/distributor_logo/zucol.png" />

	<style>
		::-webkit-scrollbar {
      width:13px;
    }
    ::-webkit-scrollbar-track {
      background: #ddd;
    }
    ::-webkit-scrollbar-thumb {
      background: #aaa;
      border:1px solid #ddd;
    }
    .fieldset  {
      border-radius: 3px;
      box-shadow: -1px 3px 20px #eaf1ff;
    }
    .select2-container--default .select2-selection--single  {
      height: 30px!important;
    }
    .select2-container--default.select2-container--disabled .select2-selection--single, select:disabled  {
      background-color: #f2faff!important;
    }
    .content-wrapper label {
      border-left: 2px solid #413fff;
      padding-left: 10px;
      background: -webkit-linear-gradient(left,#f2faff,#ffffff00);
      padding: 6px;
      font-family: sans-serif;
      font-weight: bold;
      color: #413fff;
    }
    .btn  {
      transition:all linear 0.2s!important;
    }
    .btn:not([disabled]):hover  {
      transform:scale(1.1);
    }
    .portlet-body {
      box-shadow: -1px 3px 20px #eaf1ff!important;
    }
    .item {
      height: 30px;
      padding: 5px 10px;
    }
    .right  {
      text-align: right;
    }
	</style>
</head>
<body class="sidebar-fixed">
	<div class="container-scroller">
		<!-- partial:../../partials/_navbar.html -->
		<?php include'superadmin_head.php';?>
		<!-- partial -->
		<div class="container-fluid page-body-wrapper">
			<?php include'superadmin_nav.php';?>
			<script type="text/javascript">
				$('select').prop('disabled', true);
			</script>
			<div class="main-panel">
				<div class="content-wrapper">
					<div class="card">
						<div class="card-body">
							<?php
			                  if(isset($_SESSION['error']))
			                  {
			                    echo "
			                      <div class='alert alert-danger alert-dismissible'>
			                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			                        <h4><i class='icon fa fa-warning'></i> Error!</h4>
			                      ".$_SESSION['error']."
			                      </div>
			                      ";
			                    unset($_SESSION['error']);
			                  }
			                  if(isset($_SESSION['success']))
			                  {
			                    echo "
			                      <div class='alert alert-success alert-dismissible'>
			                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			                        <h4><i class='icon fa fa-check'></i> Success!</h4>
			                        ".$_SESSION['success']."
			                      </div>
			                      ";
			                    unset($_SESSION['success']);
			                  }					
							?>
							
							<div class="row mb-3">
								<div class="col-md-10">
									<h3 class="text-info">Add GSTR Report</h3>
								</div>
								<div class="col-md-2">
									<a href="gst_file_report.php" class="btn btn-primary btn-sm pull-right">Back</a>
								</div>
							</div>
							<form method="post" name="form1" class="fieldset p-4" enctype="multipart/form-data">
								<div class="row form-group">
									<div class="col-md-6 mb-4">
										<label>Client Name <i class="text-danger">*</i></label>
										<select class="select2 form-control chk-form" title="Client Name" name="searchuser" required onchange="filter_services(this)">
											<option value="">Select Client</option><?php
											$select_client=mysqli_query($con,"SELECT `d`.`distributor_id`,`d`.`distributor_name`,`d`.`phone_no`,`d`.`place_of_supply`,`d`.`oid` FROM `tbl_distributor` as `d` inner join `tbl_client_service` as `s` on `d`.`distributor_id`=`s`.`distributor_id` and `d`.`company_id`='$company_id' and `s`.`gst`='Yes' and `d`.`compliance_status`='Active' and trim(`d`.`gstin_no`)!=''");
											while($fetch_client=mysqli_fetch_array($select_client))
											{
												?><option value="<?php echo $fetch_client['distributor_id']; ?>"><?php echo $fetch_client['distributor_name']." - ".$fetch_client['phone_no']." - ".$fetch_client['place_of_supply']." - ".$fetch_client['oid']; ?></option><?php
											}
											?>
										</select>
									</div>
									<div class="col-md-6 mb-4">
										<label>Report Type <sup style="color:#ca2115; font-size:15px;"><b>*</b></sup></label>
										<select class="form-control" name="reporttype"  id="reporttype" onchange='toggleElements(this)' disabled required>
											<option value="">Select Type</option>
										</select> 
									</div>
									<!-- --------------------Financial Year --------------------->
									<div class="col-md-6 mb-4 fy ym qua all_ele_container" style="display:none;">
										<label>Financial Year<sup style="color:#ca2115; font-size:15px;"><b>*</b></sup></label>
										<select name="financial_year" class="form-control yme quae fye all_ele" disabled required >
											<option value="">Select</option>
											<?php for($kl=(date("Y")-3);$kl<=date("Y");$kl++) { ?>
												<option value="<?php echo $kl.'-'.($kl+1); ?>" ><?php echo $kl."-".($kl+1); ?></option>
											<?php } ?>
										</select>
									</div>
									<!-- --------------------Quarter --------------------->
									<div class="col-md-6 mb-4 qua all_ele_container" style="display:none;">
										<label>Quarter <sup style="color:#ca2115; font-size:15px;"><b>*</b></sup></label>
										<select name="financial_quarter" class="form-control quae all_ele" disabled required >
											<option value="">Select</option>
											<?php for($kl=3;$kl<=12;$kl=$kl+3) { ?>
												<option value="<?php if($kl>10){ echo $kl;} else {echo '0'.$kl;} ?>" >
													<?php
													if($kl==3)	{
														echo 'Jan - Mar';
													}
													elseif($kl==6)	{
														echo 'Apr - Jun';
													}
													elseif($kl==9)	{
														echo 'Jul - Sep';
													}
													else	{
														echo 'Oct - Dec';
													}
													?>
												</option>
											<?php } ?>
										</select>
									</div>
									<!-- --------------------Month --------------------->
									<div class="col-md-6 mb-4 ym all_ele_container" style="display:none;">
										<label> Month <sup style="color:#ca2115; font-size:15px;"><b>*</b></sup></label>
										<select name="financial_month" class="form-control yme all_ele" disabled required >
											<option value="">Select</option>
											<option value='01'>January</option>
											<option value='02'>February</option>
											<option value='03'>March</option>
											<option value='04'>April</option>
											<option value='05'>May</option>
											<option value='06'>June</option>
											<option value='07'>July</option>
											<option value='08'>August</option>
											<option value='09'>September</option>
											<option value='10'>October</option>
											<option value='11'>November</option>
											<option value='12'>December</option>
										</select>
									</div>
									<!-- --------------------Report File ---------------------> 
									<div class="col-md-6 mb-4 r_file all_ele_container" style="display:none;">
										<label>Report File <sup style="color:#ca2115; font-size:15px;"><b>*</b></sup></label>
										<input type="file" class="form-control" required name="fileUpload" id="fileUpload" disabled accept=".pdf" onchange="ValidateExtension()">
									</div>
								</div>
								
								<div class="col-md-12 mb-4">
									<span id="lblError" style="color: red;"></span>
								</div>
								
								<div class="col-md-12 text-right p-0">
									<button type="submit" name="upload_report" id='submit_btn' class="btn btn-primary btn-sm" disabled>Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<?php
					if(isset($_POST['upload_report']))	{

						$client_id = $_POST['searchuser'];
						$category_id = $_POST['reporttype'];
						$r_fyear = $_POST['financial_year'];
						$filename = $_FILES['fileUpload']['name'];
						$temp=$_FILES['fileUpload']['tmp_name'];
						$info = new SplFileInfo($filename);
						$ext_val=$info->getExtension();
						$target_path  = "../../bthawk/financial_GSTR_report/";
						date_default_timezone_set('Asia/Calcutta');
						$c_time  = date('h:i:s');
						$c_date  = date("Y-m-d");
						$t=time();

						$year_list = explode('-', $r_fyear);

						$sub_category = mysqli_query($con,"SELECT * FROM `tbl_btcw_services_sub_category` where service_category_id='$category_id'");

						$row = mysqli_fetch_assoc($sub_category);

						if($row['status']=='Active')	{
							$gstin_no = mysqli_query($con, "SELECT trim(`gstin_no`) as gstin_no from tbl_distributor where distributor_id = '$client_id'");
							$gno =  mysqli_fetch_assoc($gstin_no);

							if( ($row['duration']=='Monthly')||($row['duration']=='Quarterly') )	{

								if($row['duration']=='Monthly')	{
									$r_month = $_POST['financial_month'];
								}
								else	{
									$r_month = $_POST['financial_quarter'];
								}

								$year_list = explode('-', $r_fyear);

								if($r_month>3)	{
									$r_year = $year_list[0];
								}
								else	{
									$r_year = $year_list[1];
								}

								if( ($row['service_category_id']==4) || ($row['service_category_id']==5) || ($row['service_category_id']==6) || ($row['service_category_id']==13) )	{

									$check1 = strpos($filename,'_'.$gno['gstin_no'].'_');

									if($row['service_category_id']==4)	{										
										$check2 = strpos($filename,"GSTR3B_");
										$check3 = strpos($filename,'_'.$r_month.$r_year);	
									}
									else if( ($row['service_category_id']==5) || ($row['service_category_id']==6) )	{
										$check2 = strpos($filename,"GSTR1_");	
										$check3 = strpos($filename,'_'.$r_month.$r_year);
									}
									else if($row['service_category_id']==13)	{
										$check2 = strpos($filename,"CMP08_");	
										if($r_month>9)	{
											$check3 = strpos($filename,'_16'.$r_year);
										}
										else if($r_month>6)	{
											$check3 = strpos($filename,'_15'.$r_year);
										}
										else if($r_month>3)	{
											$check3 = strpos($filename,'_14'.$r_year);
										}
										else 	{
											$check3 = strpos($filename,'_13'.$r_year);
										}
									}

									if(($check1===false) || ($check2===false) || ($check3===false))	{
										$_SESSION['error'] = "You are not uploading a valid GST report";
										header('Location:'.$_SERVER[PHP_SELF]);
										exit();
									}
								}

								$duplicate = mysqli_query($con,"SELECT * FROM `tbl_distributor_gstr_financial_report` where service_id='$row[service_category_id]' and month='$r_month' and year='$r_year' and distributor_id='$client_id'");
								if(mysqli_num_rows($duplicate)>0)	{
									$_SESSION['error'] = "Report already present for this duration";
									header('Location:'.$_SERVER[PHP_SELF]);
									exit();
								}
								else	{
									if($category_id==9)	{
										$category_id=4;
										$msg = "Pending";
									}
									else	{
										$msg = "Accept";	
									}
									$range = mysqli_query($con,"SELECT * from tbl_btcw_distributor_fees_details where fees_id in ( SELECT fees_id from tbl_btcw_distributor_fees where distributor_id = '$client_id' and distributor_fees_status in (1,2) ) and fees_detail_status=1 and service_duration='$row[duration]' and DATE_FORMAT('$r_year-$r_month-15','%Y-%m-%d') BETWEEN start_date and end_date and service_id in (SELECT id FROM `tbl_btcw_services` where service_sub_category_id='$category_id')");
									if(mysqli_num_rows($range)>0)	{
										$report_name = $row['category_name'].'_'.$r_month.$r_year.'_'.$t.'.'.$ext_val;
										move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_path.$report_name);

										$result = mysqli_query($con,"INSERT INTO `tbl_distributor_gstr_financial_report`(`distributor_id`, `year`, `month`, `financial_year`, `report_type`, `service_id`, `report_url`, `return_category`, `upload_by`, `insert_date`, `insert_time`, `system_insert_date_time`, `status`) VALUES ('$client_id', '$r_year', '$r_month', '$r_fyear', '$row[category_name]', '$row[service_category_id]', '$report_name', '$row[duration]', '$admin_id', '$da', '$ti', '$da $ti', '$msg')");

										if($row[service_category_id]==4)	{
											$result = mysqli_query($con,"UPDATE `tbl_distributor_gstr_financial_report` set `status`='Accept' where `distributor_id` = '$client_id' and `year` = '$r_year' and `month` = '$r_month' and `service_id`=9");
										}

										if($result)	{
											$_SESSION['success'] = "Report successfully uploaded";	
										}
									}
									else	{
										$_SESSION['error'] = "Service does not exist for this duration";
										header('Location:'.$_SERVER[PHP_SELF]);
										exit();		
									}

								}

							}
							else if($row['duration']=='Yearly')	{
								if( ($row['service_category_id']==7) || ($row['service_category_id']==8) )	{

									$check1 = strpos($filename,'_'.$gno['gstin_no'].'_');

									if($row['service_category_id']==7)	{
										$check2 = strpos($filename,"GSTR9_");
									}
									else 	{
										$check2 = strpos($filename,"GSTR-9C_");	
									}
									$check3 = strpos($filename,$r_year);	

									if(($check1===false) || ($check2===false) || ($check3===false))	{
										$_SESSION['error'] = "You are not uploading a valid GST report";
										header('Location:'.$_SERVER[PHP_SELF]);
										exit();
									}
								}

								$duplicate_query = mysqli_query($con,"SELECT * FROM `tbl_distributor_gstr_financial_report` where service_id='$row[service_category_id]' and financial_year='$r_fyear' and distributor_id='$client_id'");

								if(mysqli_num_rows($duplicate)>0)	{
									$_SESSION['error'] = "Report already present for this duration";
									header('Location:'.$_SERVER[PHP_SELF]);
									exit();
								}
								else	{
									$range = mysqli_query($con,"SELECT * from tbl_btcw_distributor_fees_details where fees_id in ( SELECT fees_id from tbl_btcw_distributor_fees where distributor_id = '$client_id' and distributor_fees_status in (1,2) ) and fees_detail_status=1 and service_duration='$row[duration]' and start_year<=$year_list[0] and end_year>=$year_list[1] and service_id in (SELECT id FROM `tbl_btcw_services` where service_sub_category_id='$category_id')");
									if(mysqli_num_rows($range)>0)	{
										$report_name = $row['category_name'].'_'.$r_month.$r_fyear.'_'.$t.'.'.$ext_val;
										move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_path.$report_name);

										$result = mysqli_query($con,"INSERT INTO `tbl_distributor_gstr_financial_report`(`distributor_id`, `financial_year`, `report_type`, `service_id`, `report_url`, `return_category`, `upload_by`, `insert_date`, `insert_time`, `system_insert_date_time`, `status`) VALUES ('$client_id', '$r_fyear', '$row[category_name]', '$row[service_category_id]', '$report_name', '$row[duration]', '$admin_id', '$da', '$ti', '$da $ti', 'Accept')");

										if($result)	{
											$_SESSION['success'] = "Report successfully uploaded";	
										}
									}
									else	{
										$_SESSION['error'] = "Service does not exist for this duration";
										header('Location:'.$_SERVER[PHP_SELF]);
										exit();		
									}

								}
							}
						}
						else	{
	 						$_SESSION['error'] = "This service is inactive. Please enable the service first.";						
						}
						header('Location:'.$_SERVER[PHP_SELF]);
						exit();
					}
				?>
		
				<?php include'footer.php';?>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
	</div>
	<!-- container-scroller ends-->
	<script type="text/javascript">
		function ValidateExtension() {
			var allowedFiles = [".pdf"];
			var fileUpload = document.getElementById("fileUpload");
			var lblError = document.getElementById("lblError");
			var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
			if (!regex.test(fileUpload.value.toLowerCase())) {
				lblError.innerHTML = "Please upload only PDF file";
				fileUpload.value='';
				return false;
			}
			lblError.innerHTML = "";
			return true;
		}
		function toggleElements(info)	{
			$('.all_ele_container').hide();

			if($("#reporttype option:selected").hasClass('Monthly'))	{
				$(".quae").attr("disabled", true);
				$(".fye").attr("disabled", true);


				$(".ym").show();
				$(".yme").attr("disabled", false);
			}
			else if($("#reporttype option:selected").hasClass('Quarterly'))	{
				$(".yme").attr("disabled", true);
				$(".fye").attr("disabled", true);


				$(".qua").show();
				$(".quae").attr("disabled", false);
			}
			else if($("#reporttype option:selected").hasClass('Yearly'))	{
				$(".yme").attr("disabled", true);
				$(".quae").attr("disabled", true);


				$(".fy").show();
				$(".fye").attr("disabled", false);
			}
			$("#fileUpload").attr("disabled", false);
			$(".r_file").show();

			$("#submit_btn").attr("disabled", false);
		}
		function filter_services(info)	{
			$("#submit_btn").attr("disabled", true);
			$(".all_ele").attr("disabled", true);
			$("#reporttype").attr("disabled", true);
			$("#fileUpload").attr("disabled", true);
			$('.all_ele_container').hide();
		    $.post("functions.php",
		    {
		    	filter_gst_report_type : true,
		      	client_id: info.value
		    },
		    function(data,status){
		      if(status=='success')	{
		      		document.getElementById('reporttype').innerHTML=data;
		      		$("#reporttype").attr("disabled", false);
		      }
		    });
		}
	</script>
</body>
</html>