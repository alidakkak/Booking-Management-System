<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>حجز موعد</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center">

    <div class="w-full max-w-md px-4 py-8">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl shadow-xl p-6 md:p-8">
            <h1 class="text-2xl font-bold mb-1 text-amber-400 text-center">
                حجز موعد
            </h1>
            <p class="text-sm text-slate-400 mb-6 text-center">
                يرجى تعبئة البيانات التالية لتأكيد الحجز.
            </p>

            {{-- رسائل عامة --}}
            <div id="global-error"
                class="hidden mb-4 rounded-lg bg-red-900/40 border border-red-500/50 px-4 py-3 text-sm text-red-100">
            </div>
            <div id="global-success"
                class="hidden mb-4 rounded-lg bg-emerald-900/40 border border-emerald-500/50 px-4 py-3 text-sm text-emerald-100">
            </div>

            <form id="bookingForm" class="space-y-4">
                @csrf

                {{-- الاسم --}}
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-slate-200 mb-1">
                        الاسم الكامل
                    </label>
                    <input type="text" id="customer_name" name="customer_name"
                        class="block w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        placeholder="مثال: محمد علي الدكاك" required>
                    <p id="error-customer_name" class="mt-1 text-xs text-red-400"></p>
                </div>

                {{-- الهاتف --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-200 mb-1">
                        رقم الجوال
                    </label>
                    <input type="text" id="phone" name="phone"
                        class="block w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        placeholder="مثال: 0555555444" required>
                    <p id="error-phone" class="mt-1 text-xs text-red-400"></p>
                </div>

                {{-- تاريخ ووقت الحجز --}}
                <div>
                    <label for="booking_date" class="block text-sm font-medium text-slate-200 mb-1">
                        تاريخ ووقت الحجز
                    </label>
                    <input type="datetime-local" id="booking_date" name="booking_date"
                        class="block w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        min="{{ now()->format('Y-m-d\TH:i') }}" required>
                    <p class="mt-1 text-[11px] text-slate-400">لا يمكن اختيار وقت في الماضي.</p>
                    <p id="error-booking_date" class="mt-1 text-xs text-red-400"></p>
                </div>

                {{-- نوع الخدمة --}}
                <div>
                    <label for="service_type" class="block text-sm font-medium text-slate-200 mb-1">
                        نوع الخدمة
                    </label>
                    <select id="service_type" name="service_type"
                        class="block w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        required>
                        <option value="">اختر الخدمة</option>
                        <option value="Haircut">قص شعر</option>
                        <option value="Beard Trim">تهذيب لحية</option>
                        <option value="Hair Color">صبغة شعر</option>
                        <option value="Massage">مساج</option>
                    </select>
                    <p id="error-service_type" class="mt-1 text-xs text-red-400"></p>
                </div>

                {{-- ملاحظات --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-200 mb-1">
                        ملاحظات إضافية (اختياري)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="block w-full rounded-lg border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        placeholder="أي تفاصيل إضافية تود ذكرها..."></textarea>
                    <p id="error-notes" class="mt-1 text-xs text-red-400"></p>
                </div>

                {{-- زر الإرسال --}}
                <div class="pt-2">
                    <button type="submit" id="submitBtn"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-amber-400 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-md hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="submitText">تأكيد الحجز</span>
                        <svg id="submitSpinner" class="hidden ml-2 h-4 w-4 animate-spin" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('bookingForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');

        const globalError = document.getElementById('global-error');
        const globalSuccess = document.getElementById('global-success');

        const fieldErrorIds = {
            customer_name: 'error-customer_name',
            phone: 'error-phone',
            booking_date: 'error-booking_date',
            service_type: 'error-service_type',
            notes: 'error-notes',
        };

        function setLoading(isLoading) {
            if (isLoading) {
                submitBtn.disabled = true;
                submitText.textContent = 'جارٍ إرسال الحجز...';
                submitSpinner.classList.remove('hidden');
            } else {
                submitBtn.disabled = false;
                submitText.textContent = 'تأكيد الحجز';
                submitSpinner.classList.add('hidden');
            }
        }

        function clearErrors() {
            globalError.classList.add('hidden');
            globalError.textContent = '';
            Object.values(fieldErrorIds).forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = '';
            });
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();
            globalSuccess.classList.add('hidden');
            globalSuccess.textContent = '';

            const customer_name = document.getElementById('customer_name').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const bookingRaw = document.getElementById('booking_date').value;
            const service_type = document.getElementById('service_type').value;
            const notes = document.getElementById('notes').value.trim();

            if (!bookingRaw) {
                const el = document.getElementById(fieldErrorIds.booking_date);
                if (el) el.textContent = 'الرجاء اختيار تاريخ ووقت الحجز.';
                return;
            }

            const booking_date = bookingRaw.replace('T', ' ') + ':00';

            setLoading(true);

            try {
                const response = await fetch("{{ url('/api/bookings') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({
                        customer_name,
                        phone,
                        booking_date,
                        service_type,
                        notes,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    globalSuccess.classList.remove('hidden');
                    globalSuccess.textContent = 'تم إنشاء الحجز بنجاح. شكراً لك!';

                    // إعادة تعيين الحقول
                    form.reset();
                } else if (response.status === 422) {
                    // أخطاء تحقق من StoreBookingRequest
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const elId = fieldErrorIds[field];
                            if (elId) {
                                const el = document.getElementById(elId);
                                if (el) el.textContent = data.errors[field].join('، ');
                            }
                        });

                        // لو كان في رسالة عامة (مثل الوقت محجوز)
                        if (data.message && !data.errors.booking_date) {
                            globalError.classList.remove('hidden');
                            globalError.textContent = data.message;
                        }
                    } else {
                        globalError.classList.remove('hidden');
                        globalError.textContent = data.message || 'هناك خطأ في البيانات المدخلة.';
                    }
                } else {
                    globalError.classList.remove('hidden');
                    globalError.textContent = data.message || 'حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.';
                }

            } catch (error) {
                console.error(error);
                globalError.classList.remove('hidden');
                globalError.textContent = 'تعذر الاتصال بالخادم، تأكد من تشغيل السيرفر ثم حاول مرة أخرى.';
            } finally {
                setLoading(false);
            }
        });
    </script>

</body>

</html>
