<template>
    <el-menu
        active-text-color="#ffd04b"
        background-color="#545c64"
        class="el-menu-vertical-demo"
        text-color="#fff"
        :default-openeds="['1']"
        :collapse="!$store.getters.siderType"
        router
    >
    <el-sub-menu index="1">
        <template #title>
        <el-icon><Edit /></el-icon>
        <span>{{$t('menus.monState')}}</span>
        </template>
        <el-menu-item index="dnsMonitor">
            <el-icon><Edit /></el-icon>
            <span>{{$t('menus.DNSState')}}</span>
        </el-menu-item>
        <el-menu-item index="dnsWWW">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monWWWTest')}}
        </el-menu-item>
    </el-sub-menu>
    <el-sub-menu index="1">
        <template #title>
        <el-icon><Edit /></el-icon>
        <span>{{$t('menus.monConfig')}}</span>
        </template>
        <el-menu-item index="dnsRoot">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monRootConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsAuth">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monAuthConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsRecurse">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monRecurseConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsManagement">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monManageConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsChain">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monChainConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsWWW">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monWWWConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsSOA">
            <el-icon><Menu /></el-icon>
            {{$t('menus.monSOAConfig')}}
        </el-menu-item>
        <el-menu-item index="dnsDebugInfo">
            <el-icon><Menu /></el-icon>
            {{$t('menus.dnsDebugInfo')}}
        </el-menu-item>
    </el-sub-menu>
    <el-menu-item index="dnsJSON">
        <el-icon><Edit /></el-icon>
        <span>{{$t('menus.JSONConfig')}}</span>
    </el-menu-item>
    <el-menu-item index="publishConfig">
        <el-icon><Promotion /></el-icon>
        <span>{{$t('menus.publishConfig')}}</span>
    </el-menu-item>
    <el-menu-item index="manageConfig">
        <el-icon><setting /></el-icon>
        <span>{{$t('menus.configSetting')}}</span>
    </el-menu-item>
    </el-menu>
</template>

<script>
    export default{
        name:'DNSMenu',
        setup(){

        }
    }    
</script>

<style>

</style>