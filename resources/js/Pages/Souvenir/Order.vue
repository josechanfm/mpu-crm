<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    預購畢業生限量專屬紀念品<br>
                    Pre-order of Graduation Gifts
                </h2>
                <div class="flex justify-end gap-2">
                    <div v-if="user" @click="logout">Logout<br>登出</div>
                    <div v-else @click="login">Login<br>登入</div>
                </div>
            </div>
        </template>
        <div class="flex justify-end gap-2 pt-5" v-if="user">
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
            <div class="m-4">
                <div>歡迎參與"畢業生限量專屬紀念品"預購活動！本校特意為準畢業生製作了3款專屬紀念品，濃縮校園記憶，注入點滴情誼，期望澳理大的關懷能陪伴畢業生走過更多的人生角色與秋冬。本次預購活動純屬自願性質，收益將全數作為大學收入用於母校發展。有興趣的同學可填寫下表，並繳付預購金額，支持本次活動。在此，祝願同學順利畢業，前程似錦！</div>
                <div>MPU warmly invite you to participate in our "Graduation Gifts (Limited Edition)" pre-ordering! Our university has crafted 3 exclusive items for graduating students, capturing campus memories and celebrating the friendships formed here. We hope that the support from MPU carried in the gifts will accompany graduates all the time as they embrace new roles in life. For interested students, please fill out the pre-order form below. Last but not least, wishing you a smooth graduation and a bright future ahead!</div>
            </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2">
                    <div v-for="product in products" :key="product.id" class="flex flex-col md:flex-row bg-gray-100 rounded-lg mb-5">
                        <div class="flex-shrink-0 w-full md:w-64 md:h-48">
                            <a-carousel :autoplay="true" dots>
                                <div v-for="(image, index) in product.images" :key="index">
                                    <img :src="image" alt="Product Image" class="object-cover w-full h-full rounded-md" />
                                </div>
                            </a-carousel>
                        </div>
                        <div class="flex-grow mt-0 md:mt-0 md:pl-4 m-2">
                            <h3 class="mt-2 text-lg font-semibold">{{ product.name }}</h3>
                            <p class="mt-1 text-gray-600" v-html="product.description"/>
                            <p class="mt-2 font-bold">${{ product.price.toFixed(2) }}</p>
                            <a-button type="primary" @click="addToCart(product)" :disabled="user == null" class="my-2">
                                Add to cart / 加入購物車
                            </a-button>
                        </div>
                    </div>
                </div>
            <div class="pt-10">
                <div class="font-bold">預購須知：</div>
                <div>本人(即購買人)已知悉並接受 (1) 此預購商品及款項一經付款，不能更改或退款；(2) 小心保管付款/領取憑證，於簽領時出示；(3) 由他人代簽領，毋需事先申請，但代領人須出示簽領憑證，因此購買人將自行承擔因轉發憑證所衍生之風險及或有之損失；(4) 按照簽領指引及時間領取預購貨品，不得另約時間領取，逾期未領者，不設補領，不設退款；(5) 簽領紀念品時，當場檢查貨品，若有明顯質量問題，可即場更換，若完成簽領，則不能以任何理由提出更換；(6) 詳情可細閱 "畢業生須知"。</div>
                <div class="font-bold">Note：</div>
                <div>I (the buyer) understand and agree to the following (1) Once the payment is made, the order cannot be changed or refunded; (2) I will keep my payment/collection receipt and present it when picking up my item(s); (3) Collection by a third party does not require prior application, but they must present the collection receipt. In this case, the buyer will bear any risks and losses associated with forwarding the receipt or related proof to the third party; (4) Items must be collected according to the provided guidelines and schedule. No alternative collection times will be accommodated, and items not collected by the deadline will not be eligible for re-collection or refunds; (5) Check my items on the spot upon collection. If there is a clear quality issue, I may request an immediate exchange. Otherwise, no exchanges can be made for any reason once the collection is accepted; (6) For more details, I shall refer to the "Graduates' Information Kit".  </div>
            </div>
            <div class="pt-20">

            </div>

        </div>

        <!-- Checkout drawer -->
        <a-drawer title="Cart Items / 購物車" :visible="selectedIsOpen" @close="selectedIsOpen = false" width="350">
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
                            <span>{{ item.qty }}</span>
                            <a-button @click="increaseCount(item)" size="small">+</a-button>
                        </div>
                        <span>${{ (item.price * item.qty).toFixed(2) }}</span>
                    </li>
                    
                </ul>
                <!-- Order Form -->
                 <!-- @submit.prevent="handleOrder"  -->
                <a-form :model="orderForm" layout="vertical" :rules="rules" 
                    @finish="handleOrderFinish"
                    class="mt-4"
                >
                    <a-form-item label="Faculty / 系所" name="faculty" class="mb-0">
                        <a-select v-model:value="orderForm.faculty" placeholder="Select your faculty / 選擇您的系所">
                            <template v-for="faculty in faculties" :key="faculty.value">
                                <a-select-option :value="faculty.value">{{ faculty.label }}</a-select-option>
                            </template>
                            <!-- <a-select-option value="science">Science / 科學</a-select-option>
                            <a-select-option value="arts">Arts / 人文</a-select-option>
                            <a-select-option value="engineering">Engineering / 工程</a-select-option> -->
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
        <a-drawer title="Orders / 訂單" :visible="orderIsOpen" @close="orderIsOpen = false" width="350">
            <h2 class="text-lg font-semibold">Your Previous Orders / 您的歷史訂單</h2>
            <div class="p-4">
                <template v-if="user.orders.length==0">
                    <p>You don't have any item ordered.</p>
                    <p>您尚已訂購的商品。</p>
                    <a-button class="mt-10 float-right" @click="selectedIsOpen = false">Close / 關閉</a-button>
                </template>
                <template v-else>
                    <div class="w-full flex justify-end pb-5">
                        <a-button :href="route('souvenir.pickupCode')" danger ghost class="float-right">
                            取件碼
                        </a-button>
                    </div>
                    <div v-for="(order, index) in user.orders" :key="index" class="mb-10">
                        <h3 class="font-bold">Order / 訂單 #{{ index + 1 }} @{{ formatDate(order.created_at) }} </h3>
                        <div v-for="item in order.items" :key="item.id" class="flex justify-between py-2">
                            <span>{{ item.name }}</span>
                            <span>{{ item.qty }} x ${{ item.price }}</span>
                        </div>
                        <a :href="route('souvenir.order.receipt', { id: order.id, uuid:order.uuid })" target="_blank">Receipt/收據</a>
                        <div class="font-bold float-right">
                            Total / 總計: ${{ order.amount }}
                        </div>
                    </div>
                </template>
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
            faculties:[
                { label: 'FAC / 應用科學學院', value: 'FAC' },
                { label: 'FHSS / 健康科學及體育學院', value: 'FHSS' },
                { label: 'FLT / 語言及翻譯學院', value: 'FLT' },
                { label: 'FAD / 藝術及設計學院', value: 'FDA' },
                { label: 'FHSS / 人文及社會科學學院', value: 'FHSS' },
                { label: 'FB / 管理科學學院', value: 'FB' },
            ],
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
            var itemAdded=false;

            if (existingItem) {
                if (existingItem.quota > existingItem.qty) {
                    existingItem.qty += 1;
                    itemAdded=true;
                }
            } else {
                this.cartItems.push({ id: product.id, name: product.name, price:product.price, quota:product.quota, qty: 1 });
                itemAdded=true;
            }

            if(itemAdded){
                this.cartItemCount = this.cartItems.reduce((total, item) => total + item.qty, 0);
                this.triggerAnimation();
                this.$message.success(`Added ${product.name} to cart`);
            }else{
                this.$message.warning(`You cannot increase the quantity beyond the quota of ${product.quota}.`);
            }

        },
        increaseCount(item) {
            if (item.qty < item.quota) {
                item.qty += 1;
                this.cartItemCount = this.cartItems.reduce((total, item) => total + item.qty, 0);
            }else{
                this.$message.warning(`You cannot increase the quantity beyond the quota of ${item.quota}.`);
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