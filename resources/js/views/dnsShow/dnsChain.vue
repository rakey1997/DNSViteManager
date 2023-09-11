<template>
    <makeFile :genFileData="genFileData"/>
    <DNSPage :queryForm="queryForm" />
</template>

<script>
    import DNSPage from "./components/dnsPage.vue";
    import makeFile from "./components/makeFile.vue";

    export default {
        name:'dnsChain',
        components:{DNSPage,makeFile},
        setup(){
            const queryForm={
                filterNameList:['区块链服务器']
            }
            const fileData={
                type:"server",
                fileNameList:{
                    fileName:"hosts-chain-all.ini",
                    fileV6Name:"hosts-chain-all-v6.ini",
                    whiteFileName:"whiteList-chain.ini",
                    whiteV6FileName:"whiteList-chain-v6.ini",
                    specialFileName:"hosts-chain-special-all.ini",
                    specialV6FileName:"hosts-chain-special-all-v6.ini",
                }
            }

            const genFileData={...queryForm, ...fileData}

            return{
                queryForm,
                genFileData
            }
        }
    }
</script>