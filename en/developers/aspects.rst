Aspects
#######

    In computing, aspect-oriented programming (AOP) is a programming paradigm which
    isolates secondary or supporting functions from the main program's business
    logic. It aims to increase modularity by allowing the separation of cross-
    cutting concerns, forming a basis for aspect-oriented software development.

    -- Wikipedia

The concept of Aspect-Oriented Programming (AOP) is fairly new to PHP. There's
currently no official AOP support in PHP, but there are some extensions and
libraries which implement this feature. In QuickAppsCMS AOP is implemented using the
`Go! PHP <http://go.aopphp.com/>`__.


The Basic Vocabulary
====================

At the heart of AOP is the **aspect**, but before we can define "aspect," we must
discuss two other terms: **point-cut** and **advise**. A point-cut represents a
moment in our source code, specifying the right moment to run our code. The code
that executes at a point-cut is called, advise, and the combination of one or more
point-cuts and advises is the **aspect**.

Typically, each class has one core behavior or concern, but in many situations, a
class may exhibit secondary behavior. For example, a class may need to call a logger
or notify an observer. Because these functionalities are secondary, their behavior
is mostly the same for all the classes that exhibit them. This scenario is called a
cross-concern; these can be avoided by using AOP.