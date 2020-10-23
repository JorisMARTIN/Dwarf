import React from 'react';
import { StyleSheet, View, Text, FlatList, Image } from 'react-native';
import { Icon, Card, Button } from 'react-native-elements';


const drawings = [
    { key: '0', title: 'dessin number 1', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
    { key: '1', title: 'incroyable bd', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
    { key: '2', title: 'fyguilmlkjhgfhj', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
    { key: '3', title: 'fyguilmlkjhgfhj', img: 'https://lh6.googleusercontent.com/qdEClaE84Yl8uiqhD1ACjzDGtRfnPKkYEVkEXV5l4RMxhGGvxVmEyMvxo_-FiZPKrRxbxGfCPq_orT2v-UgS-f0nCn3i-4llcesuqluVMexdLmAHeH3vgYLIniXtFzu8pQIyuLklyho' },
];

const Drawing = ({ item }) => {
    return (
        <Card>
            <Card.Title>{item.title}</Card.Title>
            <Card.Divider />
            <Card.Image source={{uri: item.img}} />
            <Button
                icon={<Icon name="arrow-upward"></Icon>}
                buttonStyle={{ borderRadius: 0, marginLeft: 0, marginRight: 0, marginBottom: 0 }}
                onPress={() => alert("upvote")}
            />
            <Button
                icon={<Icon name="arrow-downward"></Icon>}
                buttonStyle={{ borderRadius: 0, marginLeft: 0, marginRight: 0, marginBottom: 0 }}
                onPress={() => alert("downvote")}
            />
        </Card>
    )
}

const Feed = () => {
    return (
        <FlatList
            contentContainerStyle={styles.container}
            data={drawings}
            renderItem={({ item }) => <Drawing {...{ item }} style={styles.drawing} />}
        />
    )
}

export default function Home() {
    return (
        <View>
            <Feed />
        </View>
    )
}

Home.navigationOptions = ({ navigation }) => ({
    headerLeft: () => (
        <Icon style={{ paddingLeft: 10 }} name='menu'
            onPress={() => navigation.navigate('Screen2')}
        />
    ),
    headerTitle: props => (
        <View {...props} style={styles.header}>
            <Text style={styles.titleLogo} >dwarf</Text>
            <Button onPress={() => navigation.navigate('Screen2')} title="Dessiner"></Button>
        </View>
    ),
    headerRight: () => (
        <Icon style={{ paddingRight: 10 }} name='person'
            onPress={() => navigation.navigate('Screen2')}
        />
    ),
    headerStyle: {
        height: 100,
    }
});


const styles = StyleSheet.create({
    container: {
        flex: 1,
        flexDirection: "row",
        justifyContent: "space-around",
    },

    header: {
        textAlign: "center",
        flex: 1,
        alignItems: "center"
    },
    titleLogo: {
        fontSize: 30,
    },
})
