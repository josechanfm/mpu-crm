<template>
    <BlankLayout title="Souvenir Purchase">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Souvenir Order Form
                </h2>
            </div>
        </template>
    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div v-if="cart.cartItems.length==0">
                You don't have any item selected.
            </div>
            <ul v-else class="w-96">
                <li v-for="item in cart.cartItems" :key="item.id" class="flex items-center justify-between mb-2">
                    <span class="w-1/2 truncate">{{ item.name }}</span>
                    <span>${{ (item.price * item.count).toFixed(2) }}</span>
                </li>
            </ul>

            <!-- Purchase Form -->
            <a-form :model="orderForm" :rules="rules" @submit.prevent="handlePayment" class="mt-4" v-if="cart.cartItems.length > 0">
                <a-form-item label="Faculty" name="faculty">
                    <a-select v-model:value="orderForm.faculty" placeholder="Select your faculty">
                        <a-select-option value="science">Science</a-select-option>
                        <a-select-option value="arts">Arts</a-select-option>
                        <a-select-option value="engineering">Engineering</a-select-option>
                    </a-select>
                </a-form-item>

                <a-form-item label="Degree" name="degree">
                    <a-select v-model:value="orderForm.degree" placeholder="Select your degree">
                        <a-select-option value="bachelor">Bachelor</a-select-option>
                        <a-select-option value="master">Master</a-select-option>
                        <a-select-option value="phd">PhD</a-select-option>
                    </a-select>
                </a-form-item>

                <a-form-item label="Contact Phone Number" name="phone">
                    <a-input type="input" v-model:value="orderForm.phone" placeholder="Enter your phone number" />
                </a-form-item>

                <a-form-item label="Personal Email (optional)" name="email">
                    <a-input type="input" v-model:value="orderForm.email" placeholder="Enter your email" />
                </a-form-item>

                <div class="flex justify-end mt-4">
                    <a-button class="ml-2" @click="selectedIsOpen = false">Close</a-button>
                    <a-button type="primary" html-type="submit">Pay</a-button>
                </div>
            </a-form>


            <form method="post" action="https://epay.mpu.edu.mo/bocpaytest/ipm/cashier">
                <div v-for="(value, field) in payment">
                    {{ field }}: {{ value }}
                    <input :name="field" :value="payment[field]" /><br>
                </div>
                <div class="text-center">
                    <a-button type="primary" html-type="submit" class="mt-5">Confirm to Pay</a-button>
                </div>
            </form>

            </div>
        </div>
    </div>

    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { ShoppingCartOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';

export default {
    components: {
        BlankLayout,
        ShoppingCartOutlined,
        dayjs,
    },
    props: {
        user:{
            type: Object,
            required: false,
        },
        cart: {
            type: Object,
            required: true,
        },
        payment:{
            type: Array,
            require: true
        }
        // cartItems: {
        //     type: Array,
        //     required: true,
        // },
    },

    data() {
        return {
            cartItemCount: 0,
            cartIconRef: null,
            selectedIsOpen: false,
            orderIsOpen: false,
            orderForm: {
                faculty: '',
                degree: '',
                phone: '',
                email: '',
            },
            rules: {
                netid: [{ required: true, message: 'Please enter your NetID' }],
                password: [{ required: true, message: 'Please enter your password' }],
                faculty: [{ required: true, message: 'Please select your faculty' }],
                degree: [{ required: true, message: 'Please select your degree' }],
                phone: [{ required: true, message: 'Please enter your phone number' }],
                email: [], // Optional
            },
        };
    },
    methods: {
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD H:s');
        },
        login(){
            this.$inertia.get(route('souvenir.login'))
        },
        logout(){
            this.$inertia.post(route('souvenir.logout'), {}, {
                onSuccess: () => {
                    console.log('Logout successful');
                    this.isLoggedIn = false; // Update login state
                    this.user = {}; // Clear user data
                },
                onError: (error) => {
                    console.error('Logout error:', error);
                },
            });
        },
        handlePayment() {
            // Handle the purchase logic here
            console.log('Purchase Data:', this.orderForm);
            this.orderForm.cartItems=this.cart.cartItems
            this.orderForm.uuid=this.cart.uuid;
            this.$inertia.post(route('souvenir.toPayment'), this.orderForm, {
                onSuccess: (page) => {
                    console.log(page);
                    // Optionally reset the form
                    this.orderForm = {
                        netid: '',
                        password: '',
                        faculty: '',
                        degree: '',
                        phone: '',
                        email: '',
                    };
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        },
        triggerAnimation() {
            const cartIcon = this.cartIconRef;
            if (cartIcon) {
                cartIcon.classList.add('drop-animation');
                setTimeout(() => {
                    cartIcon.classList.remove('drop-animation');
                }, 300);
            }
        },
    },
};
</script>

<style scoped>
@keyframes drop-animation {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(20px);
    }
}

.drop-animation {
    animation: drop-animation 0.3s forwards;
}

/* Additional styling */
</style>