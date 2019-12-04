<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar"><?php echo e(__("Tour Attributes")); ?></h1>
        </div>
        <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <div class="col-md-4 mb40">
                <div class="panel">
                    <div class="panel-title"><?php echo e(__("Add Attributes")); ?></div>
                    <div class="panel-body">
                        <form action="<?php echo e(url('admin/module/tour/attribute/store')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <?php echo $__env->make('Tour::admin/attribute/form',['parents'=>$rows], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div class="">
                                <button class="btn btn-primary" type="submit"><?php echo e(__("Add new")); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel">
                    <div class="panel-title"><?php echo e(__("All Attributes")); ?></div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="60px"><input type="checkbox" class="check-all"></th>
                                <th><?php echo e(__("Name")); ?></th>
                                <th class=""><?php echo e(__("Actions")); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($rows) > 0): ?>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><input type="checkbox" class="check-item" name="ids[]" value="<?php echo e($row->id); ?>">
                                        </td>
                                        <td class="title">
                                            <a href="<?php echo e(url('admin/module/tour/attribute/edit/'.$row->id)); ?>"><?php echo e($row->name); ?></a>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(url('admin/module/tour/attribute/edit/'.$row->id)); ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> <?php echo e(__('Edit')); ?>

                                            </a>
                                            <a href="<?php echo e(url('admin/module/tour/attribute/terms/'.$row->id)); ?>" class="btn btn-sm btn-success"><i class="fa fa"></i> <?php echo e(__("Manage Terms")); ?>

                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3"><?php echo e(__("No data")); ?></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\booking-core\modules/Tour/Views/admin/attribute/index.blade.php ENDPATH**/ ?>