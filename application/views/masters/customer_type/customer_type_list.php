<?php $this->load->view('include/header'); ?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
        <h3 class="title">
            <?php echo $this->title; ?>
        </h3>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
        <p class="pull-right top-p">
            <button type="button" class="btn btn-xs btn-info" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
        </p>
    </div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
            <label>Name</label>
            <input type="text" class="form-control input-sm" name="name" value="<?php echo $name; ?>" />
        </div>

        <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
            <label class="display-block not-show">buton</label>
            <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
        </div>
        <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
            <label class="display-block not-show">buton</label>
            <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i
                    class="fa fa-retweet"></i> Reset</button>
        </div>
    </div>
    <hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped border-1">
            <thead>
                <tr>
                    <th class="fix-width-60 middle text-center">#</th>
                    <th class="min-width-250 middle">Name</th>
										<th class="fix-width-150 middle">Last update</th>
										<th class="fix-width-150 middle">Last sync</th>
                    <th class="fix-width-100"></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data)) : ?>
                <?php $no = $this->uri->segment($this->segment) + 1; ?>
                <?php foreach($data as $rs) : ?>
                <tr>
                    <td class="middle text-center"><?php echo $no; ?></td>
                    <td class="middle"><?php echo $rs->name; ?></td>
										<td class="middle"><?php echo thai_date($rs->date_upd, TRUE); ?></td>
										<td class="middle"><?php echo (empty($rs->last_sync) ? "" : thai_date($rs->last_sync, TRUE)); ?></td>
                    <td class="text-right">
											<?php if($this->pm->can_edit) : ?>
                        <button type="button" class="btn btn-mini btn-warning"
                            onclick="getEdit('<?php echo $rs->id; ?>')">
                            <i class="fa fa-pencil"></i>
                        </button>
											<?php endif; ?>
                    </td>
                </tr>
                <?php $no++; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/customer_type.js?v=<?php echo date('Ymd');?>"></script>

<?php $this->load->view('include/footer'); ?>
