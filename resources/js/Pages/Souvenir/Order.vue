<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Souvenir Order
                </h2>
                <div class="flex justify-end gap-2">
                    <div v-if="user" @click="logout">Logout / 登出</div>
                    <div v-else @click="login">Login / 登入</div>
                </div>
            </div>
        </template>
        <div class="flex justify-end gap-2"  v-if="user">
            <div class="relative" ref="cartIconRef" @click="selectedIsOpen = true">
                <a-badge :count="cartItemCount">
                    <ShoppingCartOutlined class="text-xl" />
                </a-badge>
                 Check Out / 結帳
            </div>
             | 
            <div class="" @click="orderIsOpen=true">
                <a-badge :count="user.orders.length" color="green" :offset="[10, -5]">
                My orders    
                </a-badge>
                 / 我的訂單
            </div>
        </div>

        <div class="py-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid gap-6">
                <div v-for="product in products" :key="product.id" class="flex bg-gray-100 rounded-lg p-4">
                    <div class="flex-shrink-0 w-64">
                    <a-carousel :autoplay="true" dots>
                        <div v-for="(image, index) in product.images" :key="index">
                        <img :src="image" alt="Product Image" class="object-cover w-full h-full rounded-md" />
                        </div>
                    </a-carousel>
                    </div>
                    <div class="flex-grow pl-4">
                    <h3 class="mt-2 text-lg font-semibold">{{ product.name }}</h3>
                    <p class="mt-1 text-gray-600">{{ product.description }}</p>
                    <p class="mt-2 font-bold">${{ product.price.toFixed(2) }}</p>
                    <a-button type="primary" @click="addToCart(product)" :disabled="user == null">
                        Add to cart / 加入購物車
                    </a-button>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

<!-- Checkout drawer -->
<a-drawer title="Cart Items / 購物車" :visible="selectedIsOpen" @close="selectedIsOpen = false" width="400">
    <div v-if="cartItems.length==0">
        <p>You don't have any item selected.</p>
        <p>您尚未選擇任何商品。</p>
        <a-button class="mt-10 float-right" @click="selectedIsOpen = false">Close / 關閉</a-button>
    </div>
    <div v-else>
        <ul>
            <li v-for="item in cartItems" :key="item.id" class="flex items-center justify-between mb-2">
                <span class="w-1/2 truncate">{{ item.name }}</span>
                <div class="flex items-center space-x-2">
                    <a-button @click="decreaseCount(item)" size="small">-</a-button>
                    <span>{{ item.count }}</span>
                    <a-button @click="increaseCount(item)" size="small">+</a-button>
                </div>
                <span>${{ (item.price * item.count).toFixed(2) }}</span>
            </li>
        </ul>
        <!-- Order Form -->
        <a-form :model="orderForm" layout="vertical" :rules="rules" @submit.prevent="handleOrder" class="mt-4">
            <a-form-item label="Faculty / 系所" name="faculty" class="mb-0">
                <a-select v-model:value="orderForm.faculty" placeholder="Select your faculty / 選擇您的系所">
                    <a-select-option value="science">Science / 科學</a-select-option>
                    <a-select-option value="arts">Arts / 人文</a-select-option>
                    <a-select-option value="engineering">Engineering / 工程</a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item label="Degree / 學位" name="degree" class="mb-0">
                <a-select v-model:value="orderForm.degree" placeholder="Select your degree / 選擇您的學位">
                    <a-select-option value="bachelor">Bachelor / 學士</a-select-option>
                    <a-select-option value="master">Master / 碩士</a-select-option>
                    <a-select-option value="phd">PhD / 博士</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item label="Contact Phone Number / 聯絡電話" name="phone" class="mb-0">
                <a-input type="input" v-model:value="orderForm.phone" placeholder="Enter your phone number / 輸入您的電話號碼" />
            </a-form-item>

            <a-form-item label="Personal Email (optional) / 個人電子郵件（可選）" name="email" class="mb-0">
                <a-input type="input" v-model:value="orderForm.email" placeholder="Enter your email / 輸入您的電子郵件" />
            </a-form-item>

            <div class="flex justify-end mt-4 gap-5">
                <a-button type="primary" html-type="submit">Pay / 付款</a-button>
                <a-button class="ml-2" @click="selectedIsOpen = false">Close / 關閉</a-button>
            </div>
        </a-form>
    </div>
</a-drawer>

        <!-- Previous order drawer -->
        <a-drawer title="Orders / 訂單" :visible="orderIsOpen" @close="orderIsOpen = false" width="400">
            <h2 class="text-lg font-semibold">Your Previous Orders / 您的歷史訂單</h2>
            <div class="p-4">
                
                <div v-for="(order, index) in user.orders" :key="index" class="mb-4">
                    <h3 class="font-bold">Order / 訂單 #{{ index + 1 }} @{{ formatDate(order.created_at) }} </h3>
                    <div v-for="item in order.items" :key="item.id" class="flex justify-between py-2">
                        <span>{{ item.name }}</span>
                        <span>{{ item.qty }} x ${{ item.price }}</span>
                    </div>
                    <div class="font-bold float-right">
                        Total / 總計: ${{ order.amount }}
                    </div>
                </div>
            </div>
        </a-drawer>

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
        products: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            cartItemCount: 0,
            cartIconRef: null,
            selectedIsOpen: false,
            orderIsOpen: false,
            cartItems: [],
            orderForm: {
                netid: '',
                password: '',
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
        addToCart(product) {
            const existingItem = this.cartItems.find(item => item.id === product.id);

            if (existingItem) {
                if (existingItem.count < 3) {
                    existingItem.count += 1;
                }
            } else {
                this.cartItems.push({ ...product, count: 1 });
            }

            this.cartItemCount = this.cartItems.reduce((total, item) => total + item.count, 0);
            this.triggerAnimation();
            console.log(`Added ${product.name} to cart`);
        },
        increaseCount(item) {
            if (item.count < item.quota) {
                item.count += 1;
            }
        },
        decreaseCount(item) {
            if (item.count > 1) {
                item.count -= 1;
            } else {
                this.cartItems = this.cartItems.filter(cartItem => cartItem.id !== item.id);
            }

            this.cartItemCount = this.cartItems.reduce((total, item) => total + item.count, 0);
        },
        handleOrder() {
            // Handle the order logic here
            this.orderForm.cartItems=this.cartItems
            
            this.$inertia.post(route('souvenir.checkout'), this.orderForm, {
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