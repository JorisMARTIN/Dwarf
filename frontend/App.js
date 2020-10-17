import React from 'react';
import { StyleSheet, Text, View, Button } from 'react-native';
import { createStackNavigator } from '@react-navigation/stack';
import { NavigationContainer } from '@react-navigation/native';

import Home from './screens/Home';

const Root = createStackNavigator();

const Screen2 = ({ route }) => {
  return <Text>{route.params.paramA}</Text>
}


export default function App() {
  return (
    <NavigationContainer>
      <Root.Navigator>
        <Root.Screen name="Home" component={Home} />
        <Root.Screen name="Screen2" component={Screen2} />
      </Root.Navigator>
    </NavigationContainer>
  )
}
