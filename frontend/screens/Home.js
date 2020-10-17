import React from 'react';
import { StyleSheet, View, Text, FlatList, Image } from 'react-native';


const drawings = [
    { key: '0', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
    { key: '1', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
    { key: '2', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
];

const Navbar = () => {
    return (
        <View>
            <Text>dwarf</Text>
        </View>
    )
}

const Feed = () => {
    return (
        <FlatList
            style={styles.container}
            data={drawings}
            renderItem={({ item }) => {
                return (
                <View>
                    <Image
                        style={styles.row}
                        source={{ uri: item.img }}
                    />
                </View>
                )
            }}
        />
    )
}

export default function Home() {
    return (
        <View>
            <Navbar />
            <Feed />
        </View>
    )
}


const styles = StyleSheet.create({
    container: {
        flex: 1,
    },
    row: {
        padding: 15,
        marginBottom: 5,
        backgroundColor: 'skyblue',
        height: 100,
    },
})
