<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <?php if ($this->pm->can_delete) : ?>
      <button type="button" class="btn btn-white btn-danger" onclick="deleteChecked()"><i class="fa fa-times"></i> Delete</button>
    <?php endif; ?>
    <?php if ($this->pm->can_add) : ?>
      <button type="button" class="btn btn-white btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add new</button>
    <?php endif; ?>
  </div>
</div>
<hr />

<form id="searchForm" method="post">
  <div class="row">
    <div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
      <label>Document No.</label>
      <input type="text" class="form-control input-sm text-center search-box" name="code" value="<?php echo $code; ?>" autofocus />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-3 col-xs-6 padding-5">
      <label>Description</label>
      <input type="text" class="form-control input-sm text-center search-box" name="name" value="<?php echo $name; ?>" />
    </div>

    <div class="col-lg-2-harf col-md-3-harf col-sm-4-harf col-xs-6 padding-5">
      <label>Promotion No.</label>      
      <select class="form-control input-sm" name="policy" id="policy">
        <option value="all">All</option>
        <option value="null" <?php echo is_selected("null", $policy); ?>>No promotion</option>
        <?php echo select_promotion($policy); ?>
      </select>
    </div>

    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
      <label>Method</label>
      <select class="form-control input-sm" name="type" id="type">
        <option value="all" <?php echo is_selected("all", $type); ?>>ทั้งหมด</option>
        <option value="P" <?php echo is_selected('P', $type); ?>>Percentage</option>
        <option value="N" <?php echo is_selected('N', $type); ?>>Price Override</option>
        <option value="F" <?php echo is_selected('F', $type); ?>>Premium</option>
      </select>
    </div>

    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
      <label>Status</label>
      <select class="form-control input-sm" name="active" id="active">
        <option value="all" <?php echo is_selected("all", $active); ?>>ทั้งหมด</option>
        <option value="1" <?php echo is_selected('1', $active); ?>>Active</option>
        <option value="0" <?php echo is_selected('0', $active); ?>>Inactive</option>
      </select>
    </div>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
      <label>Priority</label>
      <select class="form-control input-sm" name="priority" id="priority">
        <option value="all" <?php echo is_selected("all", $priority); ?>>ทั้งหมด</option>
        <option value="1" <?php echo is_selected('1', $priority); ?>>1</option>
        <option value="2" <?php echo is_selected('2', $priority); ?>>2</option>
        <option value="3" <?php echo is_selected('3', $priority); ?>>3</option>
        <option value="4" <?php echo is_selected('4', $priority); ?>>4</option>
        <option value="5" <?php echo is_selected('5', $priority); ?>>5</option>
        <option value="6" <?php echo is_selected('6', $priority); ?>>6</option>
        <option value="7" <?php echo is_selected('7', $priority); ?>>7</option>
        <option value="8" <?php echo is_selected('8', $priority); ?>>8</option>
        <option value="9" <?php echo is_selected('9', $priority); ?>>9</option>
        <option value="10" <?php echo is_selected('10', $priority); ?>>10</option>
      </select>
    </div>
    <div class="col-lg-1-harf col-md-2-harf col-sm-3 col-xs-6 padding-5">
      <label>Created at</label>
      <div class="input-daterange input-group width-100">
        <input type="text" class="width-50 text-center from-date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" placeholder="From date" />
        <input type="text" class="width-50 text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" placeholder="To date" />
      </div>
    </div>    

    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">search</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">reset</label>
      <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
  </div>

  <input type="hidden" name="search" value="1" />
</form>

<hr class="padding-5" />
<?php echo $this->pagination->create_links(); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-narrow border-1" style="min-width:1200px;">
      <thead>
        <tr>
          <?php if ($this->pm->can_delete) : ?>
            <th class="fix-width-40 text-center">
              <label>
                <input type="checkbox" class="ace" id="check-all" onchange="checkAll(this)" />
                <span class="lbl"></span>
              </label>
            </th>
          <?php endif; ?>
          <th class="fix-width-90">Actions</th>
          <th class="fix-width-40 text-center">#</th>
          <th class="fix-width-80">Create at</th>
          <th class="fix-width-100">Document No.</th>
          <th class="min-width-250">Description</th>
          <th class="fix-width-90">Method</th>
          <th class="fix-width-100">Promotion No.</th>
          <th class="fix-width-70 text-center">Status</th>
          <th class="fix-width-60 text-center">Priority</th>
          <th class="fix-width-130">Last Modified</th>
          <th class="fix-width-150">Modified by</th>
        </tr>
      </thead>
      <tbody>
        <?php $pageNo = $this->uri->segment($this->segment); ?>
        <?php if (!empty($data)) : ?>
          <?php $no = $this->uri->segment($this->segment) + 1; ?>
          <?php $promo = []; ?>
          <?php foreach ($data as $rs) : ?>
          <?php if(!isset($promo[$rs->id_policy ?? 'x'])) : ?>
          <?php $promo[$rs->id_policy ?? 'x'] = get_promotion_code($rs->id_policy); ?>
          <?php endif; ?>
          <?php $policy_code = $promo[$rs->id_policy ?? 'x']; ?>
            <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
              <?php if ($this->pm->can_delete) : ?>
                <td class="middle text-center">
                  <label>
                    <input type="checkbox" class="ace chk" value="<?php echo $rs->id; ?>" />
                    <span class="lbl"></span>
                  </label>
                </td>
              <?php endif; ?>
              <td class="middle">
                <button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->id; ?>', '<?php echo $pageNo; ?>')"><i class="fa fa-eye"></i></button>
                <?php if ($this->pm->can_edit) : ?>
                  <button type="button" class="btn btn-minier btn-warning" onclick="edit('<?php echo $rs->id; ?>', '<?php echo $pageNo; ?>')"><i class="fa fa-pencil"></i></button>
                <?php endif; ?>
                <?php if ($this->pm->can_delete) : ?>
                  <button type="button" class="btn btn-minier btn-danger" onclick="confirmDelete('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
                <?php endif; ?>
              </td>
              <td class="middle text-center no"><?php echo number($no); ?></td>
              <td class="middle"><?php echo thai_date($rs->date_add); ?></td>
              <td class="middle"><?php echo $rs->code; ?></td>
              <td class="middle"><?php echo $rs->name; ?></td>
              <td class="middle">
                <?php echo ($rs->type == 'N' ? 'Price Override' : ($rs->type == 'F' ? 'Premium' : 'Percentage')); ?>
              </td>
              <td class="middler">
                <?php if(! empty($rs->id_policy)) : ?>
                  <a href="javascript:viewPolicyDetail(<?php echo $rs->id_policy; ?>)"><?php echo $policy_code; ?></a>
                <?php endif; ?>
              </td>
              <td class="middle text-center">
                <?php if ($this->pm->can_edit) : ?>
                  <label style="height: 22px;">
                    <input class="ace ace-switch ace-switch-6" type="checkbox" value="1" onchange="toggleActive(this, <?php echo $rs->id; ?>)" <?php echo $rs->active ? 'checked' : ''; ?>>
                    <span class="lbl"></span>
                  </label>
                <?php else : ?>
                  <?php echo is_active($rs->active); ?>
                <?php endif; ?>
              </td>
              <td class="middle text-center"><?php echo $rs->priority; ?></td>
              <td class="middle"><?php echo thai_date($rs->date_upd, TRUE); ?></td>
              <td class="middle"><?php echo $rs->update_user; ?></td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>

        <?php else : ?>
          <tr>
            <td colspan="12" class="text-center">
              <h4> --- No data ---</h4>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  $('#policy').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/discount/rule/rule.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>