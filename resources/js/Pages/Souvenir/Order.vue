<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    “澳理大小熊”購買專頁<br>
                    MPU Bear Order Form
                </h2>
                <div class="flex justify-end gap-2">
                    <button v-if="user" @click="logout"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center min-w-[100px]">
                        <span>Logout</span>
                        <span class="ml-1 text-red-100">/ 登出</span>
                    </button>
                    <button v-else @click="login"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center min-w-[100px]">
                        <span>Login</span>
                        <span class="ml-1 text-blue-100">/ 登入</span>
                    </button>
                </div>

            </div>
        </template>

        <div class="flex justify-end gap-3 pt-5 px-2" v-if="user">
            <!-- Cart/Checkout Button -->
            <button ref="cartIconRef" @click="selectedIsOpen = true"
                class="relative px-4 py-2 bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 rounded-lg transition-all duration-200 shadow-sm hover:shadow flex items-center gap-2 group">
                <a-badge :count="cartItemCount" :overflow-count="99">
                    <ShoppingCartOutlined class="text-xl text-gray-600 group-hover:text-blue-600" />
                </a-badge>
                <span class="hidden sm:inline text-gray-700 group-hover:text-blue-600 font-medium">Check Out / 結帳</span>
                <span class="sm:hidden text-gray-700 group-hover:text-blue-600 font-medium">Cart</span>
            </button>

            <!-- Orders Button -->
            <button @click="orderIsOpen = true"
                class="relative px-4 py-2 bg-white border border-gray-200 hover:border-green-300 hover:bg-green-50 rounded-lg transition-all duration-200 shadow-sm hover:shadow flex items-center gap-2 group">
                <a-badge :count="user?.orders?.length || 0" color="green" :overflow-count="99">
                    <span class="text-xl text-gray-600 group-hover:text-green-600">📋</span>
                </a-badge>
                <span class="hidden sm:inline text-gray-700 group-hover:text-green-600 font-medium">My orders /
                    我的訂單</span>
                <span class="sm:hidden text-gray-700 group-hover:text-green-600 font-medium">Orders</span>
            </button>
        </div>

        <div class="py-4 sm:py-6">
            <div class="mx-4 mb-6 text-sm sm:text-base text-gray-600 space-y-2">
                <p>歡迎購買 “澳理大小熊”！本校特意為準畢業生製作了“澳理大小熊”，濃縮校園記憶，注入點滴情誼，期望澳理大的關懷能陪伴準畢業生走過更多的人生角色與秋冬。是次銷售只向本校準畢業生開放，收益將全數作為大學收入用於母校發展。欲選購的同學可先憑學生帳號登入本購買專頁，將小熊商品加至購買車，並提交支付。<strong>購買前請務必閱讀 “購買須知”</strong>。在此，祝願同學順利畢業，前程似錦！</p>
                <p>Welcome! We have created a special MPU Bear just for you (graduating students) to capture your memories here and carry the University's warmth into your future. The ordering of the bear is now only open to MPU’s graduating students, with all proceeds supporting our alma mater’s development. To buy one, please log in with your student account, add the bear to the cart, and proceed to payment. <strong>You should also read “Purchase Note” before the purchase.</strong> Last but not least, we wish you a smooth graduation and a bright future ahead!</p>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3 sm:p-4">
                <div v-for="product in products" :key="product.id"
                    class="flex flex-col sm:flex-row bg-gray-50 rounded-xl mb-4 overflow-hidden hover:shadow-md transition-shadow border border-gray-100">

                    <!-- Image Container - Fixed aspect ratio for mobile -->
                    <div class="w-full sm:w-64 flex-shrink-0 bg-gray-200" style="aspect-ratio: 16 / 10;">
                        <a-carousel :autoplay="true" dots class="h-full">
                            <div v-for="(image, index) in product.images" :key="index" class="h-full">
                                <img :src="image" :alt="product.name"
                                    class="w-full h-full object-cover object-center" />
                            </div>
                        </a-carousel>
                    </div>
                    <!-- Content Container -->
                    <div class="flex-grow p-4 sm:p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ product.name }}</h3>
                        <p class="text-sm text-gray-600 mb-2 line-clamp-none sm:line-clamp-2"
                            v-html="product.description" />
                        <p class="text-lg font-bold text-blue-600 mb-2">${{ product.price.toFixed(2) }}</p>

                        <a-button type="primary" @click="addToCart(product)"
                            :disabled="user == null || !isAvailable(product)" :class="[
                                'w-full sm:w-auto transition-all',
                                user == null || !isAvailable(product)
                                    ? 'opacity-50 cursor-not-allowed'
                                    : 'hover:scale-105'
                            ]">
                            <span class="flex items-center justify-center gap-2">
                                <ShoppingCartOutlined v-if="!user" class="text-base" />
                                {{ getButtonText(product) }}
                            </span>
                        </a-button>
                    </div>
                </div>
            </div>
            <!-- Terms and Conditions -->
             <PurchaseNote/>
        </div>

        <!-- Checkout drawer - Mobile optimized -->
        <a-drawer title="Shopping Cart / 賟物車 " :visible="selectedIsOpen" @close="selectedIsOpen = false" placement="right"
            :width="windowWidth <= 640 ? '100%' : '400px' "   :body-style="{ padding: '10px' }">
            <div v-if="cartItems.length === 0" class="text-center py-8">
                <p class="text-gray-500">You don't have any item selected.</p>
                <p class="text-gray-500 mb-6">您尚未選擇任何商品。</p>
                <a-button @click="selectedIsOpen = false" class="mt-4">Close / 關閉</a-button>
            </div>
            <div v-else class="flex flex-col h-full">
                <div class="flex-1 overflow-y-auto">
                    <ul class="space-y-3">
                        <li v-for="item in cartItems" :key="item.id"
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium mb-2 sm:mb-0 truncate">{{ item.name }}</span>

                            <div class="flex items-center justify-between sm:justify-end gap-4">
                                <div class="flex items-center space-x-2 bg-white rounded-lg border">
                                    <a-button @click="decreaseCount(item)" size="small" type="text"
                                        class="!px-3 hover:bg-gray-100">
                                        -
                                    </a-button>
                                    <span class="w-8 text-center">{{ item.qty }}</span>
                                    <a-button @click="increaseCount(item)" size="small" type="text"
                                        class="!px-3 hover:bg-gray-100">
                                        +
                                    </a-button>
                                </div>
                                <span class="font-semibold text-blue-600 w-20 text-right">
                                    ${{ (item.price * item.qty).toFixed(2) }}
                                </span>
                            </div>
                        </li>
                    </ul>

                    <!-- Order Form -->
                    <a-form ref="orderForm" :model="orderForm" layout="vertical" :rules="rules" @finish="handleOrderFinish"
                        class="mt-6 space-y-3">

                        <a-form-item label="Faculty / 系所" name="faculty" class="mb-0">
                            <a-select v-model:value="orderForm.faculty" placeholder="Select your faculty / 選擇您的系所"
                                class="w-full">
                                <a-select-option v-for="faculty in faculties" :key="faculty.value"
                                    :value="faculty.value">
                                    {{ faculty.label }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="Degree / 學位" name="degree" class="mb-0">
                            <a-select v-model:value="orderForm.degree" placeholder="Select your degree / 選擇您的學位"
                                class="w-full">
                                <a-select-option value="BACHALOR">Bachelor / 學士</a-select-option>
                                <a-select-option value="MASTER">Master / 碩士</a-select-option>
                                <a-select-option value="PHD">PhD / 博士</a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="Contact Phone Number / 聯絡電話" name="phone" class="mb-0">
                            <a-input type="input" v-model:value="orderForm.phone" placeholder="Enter your phone number / 輸入您的電話號碼"
                                class="w-full" />
                        </a-form-item>

                        <a-form-item label="Personal Email (optional) / 個人電子郵件（可選）" name="email" class="mb-0">
                            <a-input type="input" v-model:value="orderForm.email" placeholder="Enter your email / 輸入您的電子郵件"
                                class="w-full" />
                        </a-form-item>
                        <a-form-item label="Acknowledgement / 知悉" name="acknowledge" class="mb-0">
                            <a-checkbox v-model:checked="orderForm.acknowledge">本人已知悉並接受 “購買須知”之內容</a-checkbox>
                        </a-form-item>
                    </a-form>
                <PurchaseNote/>
                </div>

                <div class="border-t pt-4 mt-4 space-y-3">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total / 總計:</span>
                        <span class="text-blue-600">${{ cartTotal.toFixed(2) }}</span>
                    </div>

                    <div class="flex gap-2">
                        <a-button type="primary" html-type="submit" class="flex-1 h-10" @click="handleOrderFinish">
                            Pay / 付款
                        </a-button>
                        <a-button @click="selectedIsOpen = false" class="flex-1 h-10">
                            Close / 關閉
                        </a-button>
                    </div>
                </div>
            </div>
        </a-drawer>

        <!-- Orders drawer - Mobile optimized -->
        <a-drawer title="Orders / 訂單" :visible="orderIsOpen" @close="orderIsOpen = false" placement="right"
            :width="windowWidth <= 640 ? '100%' : '400px'">
            <div class="h-full flex flex-col">
                <h3 class="text-lg font-semibold mb-4">Your Previous Orders / 您的歷史訂單</h3>

                <div class="flex-1 overflow-y-auto">
                    <template v-if="!user?.orders?.length">
                        <div class="text-center py-8 text-gray-500">
                            <p>You don't have any item ordered.</p>
                            <p>您尚已訂購的商品。</p>
                        </div>
                    </template>
                    <template v-else>
                        <div class="mb-4 text-right">
                            <a-button :href="route('souvenir.pickupCode')" danger ghost size="small">
                                取件碼
                            </a-button>
                        </div>

                        <div v-for="(order, index) in user.orders" :key="index" class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-semibold">Order #{{ index + 1 }}</h4>
                                <span class="text-sm text-gray-500">{{ formatDate(order.created_at) }}</span>
                            </div>

                            <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm py-1">
                                <span class="truncate flex-1">{{ item.name }}</span>
                                <span class="ml-4">{{ item.qty }} x ${{ item.price }}</span>
                            </div>

                            <div class="mt-3 pt-3 border-t flex justify-between items-center">
                                <a-button :href="route('souvenir.order.receipt', { id: order.id, uuid: order.uuid })"
                                    target="_blank" size="small">
                                    Receipt/收據
                                </a-button>
                                <span class="font-bold">Total: ${{ order.amount }}</span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t pt-4 mt-4">
                    <a-button @click="orderIsOpen = false" block>Close / 關閉</a-button>
                </div>
                
            </div>
        </a-drawer>
    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { ShoppingCartOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import PurchaseNote from './PurchaseNote.vue';

export default {
    components: {
        BlankLayout,
        ShoppingCartOutlined,
        PurchaseNote
    },
    props: {
        user: {
            type: Object,
            required: false,
            default: () => ({ orders: [] })
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
            windowWidth: window.innerWidth,
            orderForm: {
                faculty: '',
                degree: '',
                phone: '',
                email: '',
                acknowledge:false
            },
            faculties: [
                { label: 'FAC / 應用科學學院', value: 'FCA' },
                { label: 'FHSS / 健康科學及體育學院', value: 'FCSD' },
                { label: 'FLT / 語言及翻譯學院', value: 'FLT' },
                { label: 'FAD / 藝術及設計學院', value: 'FAD' },
                { label: 'FHSS / 人文及社會科學學院', value: 'FCHS' },
                { label: 'FB / 管理科學學院', value: 'FCG' },
                { label: 'AE / 北京大學醫學部——澳門理工大學護理書院', value: 'AE' },
            ],
            rules: {
                faculty: [{ required: true, message: 'Please select your faculty' }],
                degree: [{ required: true, message: 'Please select your degree' }],
                phone: [
                    { required: true, message: 'Please enter your phone number' },
                    { pattern: /^[0-9]+$/, message: 'Phone number must contain only numbers' },
                ],
                email: [
                    { type: 'email', message: 'Please enter a valid email address' },
                ],
                acknowledge: [{ 
                    required: true, 
                    message: 'You must agree to the terms!',
                    validator: (_, value) => 
                    value ? Promise.resolve() : Promise.reject('Please check the box')
                }]
            },
        };
    },
    computed: {
        cartTotal() {
            return this.cartItems.reduce((total, item) => total + (item.price * item.qty), 0);
        }
    },
    mounted() {
        window.addEventListener('resize', this.handleResize);
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize);
    },
    methods: {
        isAvailable(product){
            //if (product.user_quota_remaining==0) return false;
            const now = new Date();
            const expiry = new Date(product.available_to);
            return expiry > now;
        },
        handleResize() {
            this.windowWidth = window.innerWidth;
        },
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD HH:mm');
        },
        login() {
            this.$inertia.get(route('souvenir.login'));
        },
        logout() {
            this.$inertia.post(route('souvenir.logout'), {}, {
                onSuccess: () => {
                    this.cartItems = [];
                    this.cartItemCount = 0;
                },
            });
        },
        getButtonText(product) {
            if (!this.user) return 'Login to buy / 登入購買';
            if (this.selectedProductCount(product.id) >= product.user_quota_remaining) {
                return `Max quota reached / 已達上限 (${product.user_quota_remaining})`;
            }
            return `Add to cart / 加入購物車 (${this.selectedProductCount(product.id)}/${product.user_quota_remaining})`;
        },
        addToCart(product) {
            if (!this.user) {
                this.$message.warning('Please login first / 請先登入');
                return;
            }

            const existingItem = this.cartItems.find(item => item.id === product.id);

            if (existingItem) {
                if (existingItem.qty < product.user_quota_remaining) {
                    existingItem.qty += 1;
                    this.triggerAnimation();
                    this.$message.success(`Added ${product.name} to cart`);
                } else {
                    this.$message.warning(`Maximum quota of ${product.user_quota_remaining} reached for this item`);
                    return;
                }
            } else {
                this.cartItems.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    user_quota_remaining: product.user_quota_remaining,
                    qty: 1
                });
                this.triggerAnimation();
                this.$message.success(`Added ${product.name} to cart`);
            }

            this.cartItemCount = this.cartItems.reduce((total, item) => total + item.qty, 0);
        },
        increaseCount(item) {
            if (item.qty < item.user_quota_remaining) {
                item.qty += 1;
                this.cartItemCount = this.cartItems.reduce((total, item) => total + item.qty, 0);
            } else {
                this.$message.warning(`Maximum quota of ${item.user_quota_remaining} reached`);
            }
        },
        decreaseCount(item) {
            if (item.qty > 1) {
                item.qty -= 1;
            } else {
                this.cartItems = this.cartItems.filter(cartItem => cartItem.id !== item.id);
            }
            this.cartItemCount = this.cartItems.reduce((total, item) => total + item.qty, 0);
        },
        handleOrderFinish() {
            if (this.cartItems.length === 0) {
                this.$message.warning('Your cart is empty / 購物車是空的');
                return;
            }
            this.$refs.orderForm.validate().then(() => {
                this.orderForm.cartItems = this.cartItems;

                this.$inertia.post(route('souvenir.checkout'), this.orderForm, {
                    onSuccess: () => {
                        this.cartItems = [];
                        this.cartItemCount = 0;
                        this.selectedIsOpen = false;
                        this.orderForm = {
                            faculty: '',
                            degree: '',
                            phone: '',
                            email: '',
                        };
                        this.$message.success('Order placed successfully!');
                    },
                    onError: (errors) => {
                        console.error('Order error:', errors);
                    },
                });
            }).catch(() => {
                // Validation failed – show a user-friendly message
                this.$message.error('Please fill in all required fields correctly / 請正確填寫所有必填欄位');
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
        selectedProductCount(productId) {
            const item = this.cartItems.find(item => item.id === productId);
            return item ? item.qty : 0;
        },
    },
};
</script>

<style scoped>
/* Animation */
@keyframes drop-animation {
    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }

    100% {
        transform: translateY(0);
    }
}

.drop-animation {
    animation: drop-animation 0.3s ease-in-out;
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .sm\:rounded-lg {
        border-radius: 0.5rem;
    }

    /* Better touch targets for mobile */
    button,
    [role="button"],
    .cursor-pointer {
        min-height: 44px;
        min-width: 44px;
    }

    /* Improve form field spacing on mobile */
    .ant-form-item {
        margin-bottom: 16px;
    }
}

/* Carousel customization */
:deep(.ant-carousel) {
    height: 100%;
}

:deep(.ant-carousel .slick-slider) {
    height: 100%;
}

:deep(.ant-carousel .slick-list) {
    height: 100%;
}

:deep(.ant-carousel .slick-track) {
    height: 100%;
}

:deep(.ant-carousel .slick-slide) {
    height: 100%;
}

:deep(.ant-carousel .slick-slide > div) {
    height: 100%;
}
.ant-drawer-body{
    padding:10px;
}
</style>