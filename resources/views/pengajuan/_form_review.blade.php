<form id="formReview-{{ $proposal->id }}"
      action="{{ route('reviewer.simpanReview', $proposal->id) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <div class="form-review-wrap">
        <div style="margin-bottom:16px;">
            <label class="form-review-lbl">Catatan Review <span style="color:#dc3545;">*</span></label>
            <textarea name="catatan" class="form-review-textarea"
                placeholder="Tuliskan catatan hasil review proposal..."
                maxlength="200">{{ $proposal->catatan ?? '' }}</textarea>
            <div style="font-size:0.75rem;color:#aaa;margin-top:4px;">Maksimal 200 karakter</div>
        </div>

        <div style="margin-bottom:20px;">
            <label class="form-review-lbl">
                File Tinjauan
                <span style="color:#aaa;font-weight:400;">(opsional, PDF/DOC maks 10MB)</span>
            </label>
            <input type="file" name="file_tinjauan" class="form-review-file-input"
                accept=".pdf,.doc,.docx">
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="button"
                onclick="submitReview('formReview-{{ $proposal->id }}')"
                class="btn-kirim-review">
                <i class="fa fa-paper-plane me-1"></i> Kirim Review
            </button>
        </div>
    </div>

</form>