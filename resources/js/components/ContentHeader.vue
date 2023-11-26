<template>
    <header class="content-header d-flex justify-between align-center py-5">
        <h3 class="title">
            <slot name="title"></slot>
        </h3>
        <NLFlex extraClass="actions" gap="2" alignItems="center" justifyContent="end">
            <slot name="actions"></slot>
        </NLFlex>
    </header>
</template>

<script>
export default {
    name: "ContentHeader",
    props: {
        title: { type: String, default: null }
    }
}
</script>

<style lang="scss" scoped></style>
