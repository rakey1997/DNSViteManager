<template>
    <el-dropdown>
        <span class="el-dropdown-link">
            <el-avatar :size="40" :src="circleUrl" />
        </span>
        <template #dropdown>
        <el-dropdown-menu>
            <el-dropdown-item @click="logout">{{$t('driver.logout')}}</el-dropdown-item>
        </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script>
    import { ref } from '@vue/reactivity'
    import { useStore } from 'vuex'
    export default {
        name:'avatar',
        setup(){
            // const circleUrl =ref('https://oa.pcl.ac.cn/ui-ext/icered/icon/l/icon_43.png')
            const circleUrl =ref('https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fblog%2F202105%2F11%2F20210511193549_08e64.thumb.1000_0.jpg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=auto?sec=1671703114&t=ce3aac9055913b0ff3b09b5ca3ca761d')
            
            const store=useStore()
            const logout=()=>{
                store.dispatch('app/logout')
            }
            return{
                circleUrl,
                logout
            }
        }
    }
</script>

<style lang="scss" scoped>
    :deep(.el-dropdown-menu__item){
        white-space: nowrap;
    }
</style>