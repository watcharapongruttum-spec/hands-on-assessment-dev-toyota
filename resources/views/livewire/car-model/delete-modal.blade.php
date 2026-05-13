<div>
    {{-- Modal 1: ระบุเหตุผล --}}
    @if($showStep1)
    <div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;" role="dialog" aria-modal="true">
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.5);" wire:click="cancel"></div>
        <div style="position:relative;background:white;border-radius:1rem;box-shadow:0 25px 50px rgba(0,0,0,0.25);width:100%;max-width:32rem;z-index:10000;">
            <div style="padding:1.5rem 1.5rem 1rem;">
                <h3 style="font-size:1.125rem;font-weight:600;color:#111;">ระบุเหตุผลการลบ</h3>
                @if($carModel)
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;">รุ่น {{ $carModel->brand }} {{ $carModel->name }} ({{ $carModel->code }})</p>
                @endif
                <div style="margin-top:1rem;">
                    <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">เหตุผลการลบ <span style="color:red;">*</span></label>
                    <select wire:model.live="deletionReason" style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;background:white;color:#111;appearance:auto;height:2.5rem;">
                        <option value="">-- เลือกเหตุผล --</option>
                        <option value="ยกเลิกการผลิต">ยกเลิกการผลิต</option>
                        <option value="ข้อมูลซ้ำ">ข้อมูลซ้ำ</option>
                        <option value="ข้อมูลไม่ถูกต้อง">ข้อมูลไม่ถูกต้อง</option>
                        <option value="อื่นๆ">อื่นๆ</option>
                    </select>
                    @error('deletionReason') <p style="color:red;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                @if($deletionReason === 'อื่นๆ')
                <div style="margin-top:1rem;">
                    <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.25rem;">รายละเอียดเพิ่มเติม <span style="color:red;">*</span></label>
                    <textarea wire:model="deletionDetail" rows="3" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;" placeholder="ระบุรายละเอียด..."></textarea>
                    @error('deletionDetail') <p style="color:red;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>
<div style="background:#f9fafb;padding:1rem 1.5rem;display:flex;justify-content:flex-end;gap:0.75rem;border-radius:0 0 1rem 1rem;">
    <button wire:click="cancel" style="padding:0.5rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;font-weight:500;background:white;color:#374151;cursor:pointer;">ยกเลิก</button>
    <button wire:click="proceedToConfirm" style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;background:#dc2626;color:white;border:none;cursor:pointer;">ถัดไป →</button>
</div>
        </div>
    </div>
    @endif

    {{-- Modal 2: ยืนยันการลบ --}}
    @if($showStep2)
    <div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;" role="dialog" aria-modal="true">
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.5);"></div>
        <div style="position:relative;background:white;border-radius:1rem;box-shadow:0 25px 50px rgba(0,0,0,0.25);width:100%;max-width:32rem;z-index:10000;">
            <div style="padding:1.5rem 1.5rem 1rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                    <div style="width:2.5rem;height:2.5rem;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                        <span style="color:#dc2626;font-size:1.25rem;">⚠️</span>
                    </div>
                    <h3 style="font-size:1.125rem;font-weight:600;color:#111;">ยืนยันการลบ</h3>
                </div>
                @if($carModel)
                <p style="font-size:0.875rem;color:#4b5563;">
                    คุณต้องการลบรุ่น <strong>{{ $carModel->brand }} {{ $carModel->name }} ({{ $carModel->code }})</strong> ใช่หรือไม่?
                </p>
                <div style="margin-top:0.75rem;padding:0.75rem;background:#f9fafb;border-radius:0.5rem;font-size:0.875rem;color:#4b5563;">
                    <strong>เหตุผล:</strong> {{ $deletionReason }}
                    @if($deletionDetail)
                    <br><strong>รายละเอียด:</strong> {{ $deletionDetail }}
                    @endif
                </div>
                @endif
            </div>
<div style="background:#f9fafb;padding:1rem 1.5rem;display:flex;justify-content:space-between;gap:0.75rem;border-radius:0 0 1rem 1rem;">
    <button wire:click="backToStep1" style="padding:0.5rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;font-weight:500;background:white;color:#374151;cursor:pointer;">← ย้อนกลับ</button>
    <div style="display:flex;gap:0.75rem;">
        <button wire:click="cancel" style="padding:0.5rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;font-weight:500;background:white;color:#374151;cursor:pointer;">ยกเลิก</button>
        <button wire:click="confirmDelete" style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;background:#dc2626;color:white;border:none;cursor:pointer;">ยืนยันการลบ</button>
    </div>
</div>
        </div>
    </div>
    @endif
</div>