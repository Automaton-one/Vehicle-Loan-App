<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import vehicles from '../../../data/vehicles.json';

const form = useForm({
    full_name: '',
    email: '',
    phone: '',
    date_of_birth: '',
    vehicle_type: '',
    vehicle_make: '',
    vehicle_model: '',
    purchase_price: 0,
    deposit_amount: 0,
    term_months: 36,
    consent_to_credit_check: false,
});

const makes = computed(() => {
    return form.vehicle_type && vehicles[form.vehicle_type]
        ? Object.keys(vehicles[form.vehicle_type])
        : [];
});

const models = computed(() => {
    return form.vehicle_type && form.vehicle_make && vehicles[form.vehicle_type]
        ? vehicles[form.vehicle_type][form.vehicle_make] || []
        : [];
});

const submitted = ref(false);

const submit = () => {
    form.transform((data) => ({
        ...data,
        consent_to_credit_check: data.consent_to_credit_check ? 1 : 0,
    }));

    form.post('/applications', {
        onSuccess: () => {
            submitted.value = true;
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="loan-form max-w-xl">
            <template v-if="submitted">
                <div class="rounded-lg bg-white p-8 text-center shadow">
                    <svg
                        class="mx-auto mb-4 h-16 w-16 text-green-500"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z"
                        />
                    </svg>
                    <h1 class="mb-2 text-2xl font-bold text-gray-800">
                        Your application has been submitted!
                    </h1>
                    <p class="mb-6 text-gray-600">
                        Thank you for applying. Our team will review your application and contact
                        you shortly.
                    </p>
                </div>
            </template>

            <template v-else>
                <h1 class="mb-4 text-2xl font-bold">Vehicle Loan Application</h1>

                <div class="space-y-4">
                    <div>
                        <label for="full_name" class="block">Full name</label>
                        <input
                            id="full_name"
                            type="text"
                            v-model="form.full_name"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.full_name" />
                    </div>
                    <div>
                        <label for="email" class="block">Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div>
                        <label for="phone" class="block">Phone</label>
                        <input
                            id="phone"
                            type="text"
                            v-model="form.phone"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div>
                        <label for="date_of_birth" class="block">Date of Birth</label>
                        <input
                            id="date_of_birth"
                            type="date"
                            v-model="form.date_of_birth"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.date_of_birth" />
                    </div>

                    <div>
                        <label for="vehicle_type" class="block">Vehicle Type</label>
                        <select
                            id="vehicle_type"
                            v-model="form.vehicle_type"
                            class="w-full border p-2"
                        >
                            <option value="">Select</option>
                            <option value="car">Car</option>
                            <option value="ute">Ute</option>
                            <option value="truck">Truck</option>
                            <option value="van">Van</option>
                            <option value="suv">SUV</option>
                        </select>
                        <InputError :message="form.errors.vehicle_type" />
                    </div>

                    <div>
                        <label for="vehicle_make" class="block">Make</label>
                        <select
                            id="vehicle_make"
                            v-model="form.vehicle_make"
                            class="w-full border p-2"
                        >
                            <option value="">Select make</option>
                            <option v-for="m in makes" :key="m" :value="m">{{ m }}</option>
                        </select>
                        <InputError :message="form.errors.vehicle_make" />
                    </div>

                    <div>
                        <label for="vehicle_model" class="block">Model</label>
                        <select
                            id="vehicle_model"
                            v-model="form.vehicle_model"
                            class="w-full border p-2"
                        >
                            <option value="">Select model</option>
                            <option v-for="m in models" :key="m" :value="m">{{ m }}</option>
                        </select>
                        <InputError :message="form.errors.vehicle_model" />
                    </div>

                    <div class="currency-input">
                        <label for="purchase_price" class="block">Purchase Price</label>
                        <input
                            id="purchase_price"
                            type="number"
                            v-model.number="form.purchase_price"
                            step="0.01"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.purchase_price" />
                    </div>

                    <div class="currency-input">
                        <label for="deposit_amount" class="block">Deposit Amount</label>
                        <input
                            id="deposit_amount"
                            type="number"
                            v-model.number="form.deposit_amount"
                            step="0.01"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.deposit_amount" />
                    </div>

                    <div>
                        <label for="term_months" class="block">Term (months)</label>
                        <input
                            id="term_months"
                            type="number"
                            v-model.number="form.term_months"
                            min="6"
                            max="96"
                            class="w-full border p-2"
                        />
                        <InputError :message="form.errors.term_months" />
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.consent_to_credit_check"
                                class="mr-2"
                            />
                            <span>I consent to a credit check</span>
                        </label>
                        <InputError :message="form.errors.consent_to_credit_check" />
                    </div>

                    <div>
                        <button
                            @click.prevent="submit"
                            :disabled="form.processing"
                            class="rounded bg-blue-600 px-4 py-2 text-white"
                        >
                            Apply Now
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </AppLayout>
</template>
