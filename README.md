# DiscountTest

Before.

I only have an afternoon to develop this, and its a pity.
Cause i can't develop unit test, or do a refactor, for example in apply discounts etc.
Why?
I receive this test today, i have an appointmen to participate in a radio program (previous job that have a radio)
until 17:00 pm, tomorrow thursday 15th of September I will be helping in the organization of the speaks "deSymfony"
the most important talks about Symfony in Spain, and the Friday are the talsk, which i will attend.
This same Friday i go after the talks abroad. (my only real hollidays a weekend)..so.. 
Its a shame, I really loved the test.


Good Thing, I finished, without test..i will attach a Postman Collection in the root
Sorry for all of this!

https://www.getpostman.com/docs/collections

Using Orders.postman_collection.json

BTW: ITs the first time i do an app in silex..but i wanted to try.


Steps (while I develop):

1. I put in app.php a small code in order to have in the container the data. I wish to have more time
in order to make it better.. just a piece of code that will give me the data inside the container, since its very small
ammount of data.
2. I create the class controller and instanciate as service in controllers.php, assigned the route /receive-order
3. I create a small trait, with some helper methods, I dont put this setter in the construct, cause traits and constructs..can be a 
 very bad idea. I also use array_column to extract the category for each product_id. Again i do this, due to the rush
 and because the set of data its very small.. big data sets..not a good idea to put in arrays obviously.
4. Also add discounts into the container. This discounts should be in the src/Discounts folder, and implement the DiscountInterface
5. **The key of this example its on the DiscountInterface!!**
6. I added the ability of create PostDiscount, meaning will be of time after all items, for example more than xEuros..etc...  
   But didnt added any logic to it..cause wasnt in the problem, but can be used later
7. The discount MoreThan1000 should be MoreThan.. apply a tier discount... probably reading from a yaml.. and some of them depending
   if there are previous discounts or not.
   

 
 


