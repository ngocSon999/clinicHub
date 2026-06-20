<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Xóa cache của Spatie tránh dính dữ liệu lỗi
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. ID chi nhánh (Team) mẫu cho ClinicHub
        $clinicHanoi = 1;
        $clinicHCM   = 2;

        // 3. Khởi tạo danh sách Quyền hạn (Permissions) ngắn gọn, phân theo Module y tế
        $permissions = [
            // Module Lịch hẹn & Tiếp đón (appointment)
            'appointment.view',
            'appointment.create',
            'appointment.edit',
            'appointment.delete',

            // Module Hồ sơ bệnh án & Khám bệnh (clinical)
            'clinical.view',        // Xem lịch sử bệnh án
            'clinical.prescribe',   // Kê đơn thuốc
            'clinical.order',       // Chỉ định cận lâm sàng (siêu âm, xét nghiệm...)

            // Module Thu ngân & Hóa đơn (billing)
            'billing.view',
            'billing.create',

            // Module Kho dược & Vật tư (inventory)
            'inventory.view',
            'inventory.manage',

            // Module Quản trị hệ thống (admin)
            'admin.report',         // Xem báo cáo doanh thu, hiệu suất
            'admin.staff',          // Quản lý tài khoản nhân viên
            'admin.setting',        // Cấu hình thông tin phòng khám
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 4. Tạo dữ liệu Role mẫu theo từng Chi nhánh (Teams)

        // ==========================================
        // --- CHI NHÁNH 1: CƠ SỞ HÀ NỘI ---
        // ==========================================

        // Quản trị viên HN: Có tất cả các quyền của hệ thống
        $adminHanoi = Role::create(['name' => 'admin', 'team_id' => $clinicHanoi, 'guard_name' => 'web']);
        $adminHanoi->syncPermissions(Permission::all());

        // Bác sĩ chuyên khoa HN: Tập trung vào chuyên môn khám chữa bệnh và lịch hẹn
        $doctorHanoi = Role::create(['name' => 'doctor', 'team_id' => $clinicHanoi, 'guard_name' => 'web']);
        $doctorHanoi->syncPermissions([
            'appointment.view',
            'appointment.create',
            'appointment.edit',
            'clinical.view',
            'clinical.prescribe',
            'clinical.order',
        ]);

        // Lễ tân / Tiếp đón HN: Quản lý lịch hẹn, tiếp đón ban đầu và lập hóa đơn tạm tính
        $receptionistHanoi = Role::create(['name' => 'receptionist', 'team_id' => $clinicHanoi, 'guard_name' => 'web']);
        $receptionistHanoi->syncPermissions([
            'appointment.view',
            'appointment.create',
            'appointment.edit',
            'appointment.delete',
            'billing.view',
            'billing.create',
        ]);


        // ==========================================
        // --- CHI NHÁNH 2: CƠ SỞ TP.HCM ---
        // ==========================================

        $adminHCM = Role::create(['name' => 'admin', 'team_id' => $clinicHCM, 'guard_name' => 'web']);
        $adminHCM->syncPermissions(Permission::all());

        $doctorHCM = Role::create(['name' => 'doctor', 'team_id' => $clinicHCM, 'guard_name' => 'web']);
        $doctorHCM->syncPermissions([
            'appointment.view',
            'clinical.view',
            'clinical.prescribe',
            'clinical.order',
        ]);

        $receptionistHCM = Role::create(['name' => 'receptionist', 'team_id' => $clinicHCM, 'guard_name' => 'web']);
        $receptionistHCM->syncPermissions([
            'appointment.view',
            'appointment.create',
            'billing.view',
        ]);

        $this->command->info('Đã tạo thành công dữ liệu mẫu Quyền và Vai trò theo Module cho các chi nhánh!');
    }
}
